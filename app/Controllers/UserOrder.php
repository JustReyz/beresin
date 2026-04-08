<?php

namespace App\Controllers;

use App\Models\PickupOrderModel;

class UserOrder extends BaseController
{
    private function requireUser()
    {
        $user = session()->get('user');

        if (! $user) {
            return redirect()->to('/login')->with('errors', ['login' => 'Silakan login terlebih dahulu.']);
        }

        if (($user['role'] ?? 'user') !== 'user') {
            return redirect()->to('/dashboard')->with('errors', ['login' => 'Akses user ditolak.']);
        }

        return null;
    }

    private function generateOrderCode(): string
    {
        return '#' . date('His') . random_int(10, 99);
    }

    public function create()
    {
        $redirect = $this->requireUser();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/dashboard')->with('errors', ['order' => 'Metode request tidak valid.']);
        }

        $rules = [
            'pickup_address' => 'required|min_length[8]|max_length[255]',
            'estimated_volume' => 'required|in_list[<50kg,50-100kg,>100kg]',
            'pickup_time' => 'required',
            'category' => 'required|in_list[Organik,Plastik,Kertas,Elektronik,B3]',
            'notes' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/dashboard')->withInput()->with('errors', $this->validator->getErrors());
        }

        $sessionUser = session()->get('user');
        $orderModel = new PickupOrderModel();

        $saved = $orderModel->insert([
            'order_code' => $this->generateOrderCode(),
            'user_id' => (int) ($sessionUser['id'] ?? 0),
            'customer_name' => (string) ($sessionUser['name'] ?? 'Pengguna'),
            'pickup_address' => (string) $this->request->getPost('pickup_address'),
            'pickup_time' => date('Y-m-d H:i:s', strtotime((string) $this->request->getPost('pickup_time'))),
            'estimated_volume' => (string) $this->request->getPost('estimated_volume'),
            'category' => (string) $this->request->getPost('category'),
            'notes' => (string) $this->request->getPost('notes'),
            'status' => 'menunggu',
            'assigned_mitra_id' => null,
        ]);

        if (! $saved) {
            return redirect()->to('/dashboard')->withInput()->with('errors', ['order' => 'Gagal membuat pesanan penjemputan.']);
        }

        return redirect()->to('/dashboard')->with('message', 'Pesanan dibuat. Sistem sedang mencari mitra terdekat.');
    }

    public function rate(int $id)
    {
        $redirect = $this->requireUser();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/dashboard')->with('errors', ['rating' => 'Metode request tidak valid.']);
        }

        $rating = (int) $this->request->getPost('rating');
        if ($rating < 1 || $rating > 5) {
            return redirect()->to('/dashboard')->with('errors', ['rating' => 'Rating harus 1 sampai 5.']);
        }

        $sessionUser = session()->get('user');
        $orderModel = new PickupOrderModel();
        $order = $orderModel->find($id);

        if (! $order) {
            return redirect()->to('/dashboard')->with('errors', ['rating' => 'Pesanan tidak ditemukan.']);
        }

        if ((int) ($order['user_id'] ?? 0) !== (int) ($sessionUser['id'] ?? 0)) {
            return redirect()->to('/dashboard')->with('errors', ['rating' => 'Anda tidak berhak memberi rating pesanan ini.']);
        }

        if (($order['status'] ?? '') !== 'selesai') {
            return redirect()->to('/dashboard')->with('errors', ['rating' => 'Rating hanya bisa diberikan untuk pesanan selesai.']);
        }

        $orderModel->update($id, [
            'rating' => $rating,
            'rated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dashboard')->with('message', 'Terima kasih, rating berhasil disimpan.');
    }
}
