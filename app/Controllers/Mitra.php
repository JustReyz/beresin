<?php

namespace App\Controllers;

use App\Models\PickupOrderModel;

class Mitra extends BaseController
{
    private function requireMitra()
    {
        $user = session()->get('user');

        if (! $user) {
            return redirect()->to('/login')->with('errors', ['login' => 'Silakan login terlebih dahulu.']);
        }

        if (($user['role'] ?? 'user') !== 'mitra') {
            return redirect()->to('/dashboard')->with('errors', ['login' => 'Akses mitra ditolak.']);
        }

        return null;
    }

    public function dashboard()
    {
        $redirect = $this->requireMitra();
        if ($redirect !== null) {
            return $redirect;
        }

        $user = session()->get('user');
        $mitraId = (int) ($user['id'] ?? 0);

        $orderModel = new PickupOrderModel();

        $availableOrders = $orderModel
            ->where('status', 'menunggu')
            ->where('assigned_mitra_id', null)
            ->orderBy('id', 'DESC')
            ->findAll();

        $myActiveOrders = $orderModel
            ->where('status', 'aktif')
            ->where('assigned_mitra_id', $mitraId)
            ->orderBy('id', 'DESC')
            ->findAll();

        $myCompletedOrders = $orderModel
            ->where('status', 'selesai')
            ->where('assigned_mitra_id', $mitraId)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('mitra/dashboard', [
            'currentUser' => $user,
            'availableOrders' => $availableOrders,
            'myActiveOrders' => $myActiveOrders,
            'myCompletedTotal' => count($myCompletedOrders),
            'message' => session()->getFlashdata('message'),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function acceptOrder(int $id)
    {
        $redirect = $this->requireMitra();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/mitra/dashboard')->with('errors', ['mitra' => 'Metode request tidak valid.']);
        }

        $user = session()->get('user');
        $mitraId = (int) ($user['id'] ?? 0);
        $orderModel = new PickupOrderModel();
        $order = $orderModel->find($id);

        if (! $order) {
            return redirect()->to('/mitra/dashboard')->with('errors', ['mitra' => 'Pesanan tidak ditemukan.']);
        }

        if (($order['status'] ?? '') !== 'menunggu' || ! empty($order['assigned_mitra_id'])) {
            return redirect()->to('/mitra/dashboard')->with('errors', ['mitra' => 'Pesanan ini sudah diambil mitra lain.']);
        }

        $orderModel->update($id, [
            'status' => 'aktif',
            'assigned_mitra_id' => $mitraId,
        ]);

        return redirect()->to('/mitra/dashboard')->with('message', 'Pesanan berhasil diterima.');
    }

    public function completeOrder(int $id)
    {
        $redirect = $this->requireMitra();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/mitra/dashboard')->with('errors', ['mitra' => 'Metode request tidak valid.']);
        }

        $user = session()->get('user');
        $mitraId = (int) ($user['id'] ?? 0);
        $orderModel = new PickupOrderModel();
        $order = $orderModel->find($id);

        if (! $order) {
            return redirect()->to('/mitra/dashboard')->with('errors', ['mitra' => 'Pesanan tidak ditemukan.']);
        }

        if ((int) ($order['assigned_mitra_id'] ?? 0) !== $mitraId || ($order['status'] ?? '') !== 'aktif') {
            return redirect()->to('/mitra/dashboard')->with('errors', ['mitra' => 'Pesanan ini bukan tugas aktif Anda.']);
        }

        $orderModel->update($id, ['status' => 'selesai']);

        return redirect()->to('/mitra/dashboard')->with('message', 'Pesanan ditandai selesai.');
    }
}
