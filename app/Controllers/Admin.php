<?php

namespace App\Controllers;

use App\Models\CourierModel;
use App\Models\PickupOrderModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    private function requireAdmin()
    {
        $user = session()->get('user');

        if (! $user) {
            return redirect()->to('/login')->with('errors', ['login' => 'Silakan login terlebih dahulu.']);
        }

        if (($user['role'] ?? 'user') !== 'admin') {
            return redirect()->to('/dashboard')->with('errors', ['login' => 'Akses admin ditolak.']);
        }

        return null;
    }

    private function seedDemoData(PickupOrderModel $orderModel, CourierModel $courierModel): void
    {
        if ($orderModel->countAllResults() === 0) {
            $orderModel->insertBatch([
                ['order_code' => '#2041', 'customer_name' => 'Ahmad Budi', 'category' => 'Organik', 'status' => 'aktif'],
                ['order_code' => '#2040', 'customer_name' => 'Siti Rahma', 'category' => 'Plastik', 'status' => 'selesai'],
                ['order_code' => '#2039', 'customer_name' => 'Hendra W.', 'category' => 'B3', 'status' => 'menunggu'],
                ['order_code' => '#2038', 'customer_name' => 'Nur Hayati', 'category' => 'Kertas', 'status' => 'selesai'],
                ['order_code' => '#2037', 'customer_name' => 'Rizky F.', 'category' => 'Organik', 'status' => 'batal'],
            ]);
        }

        if ($courierModel->countAllResults() === 0) {
            $courierModel->insertBatch([
                ['name' => 'Ahmad Fauzi', 'today_completed' => 3, 'today_active' => 1, 'availability_status' => 'bertugas'],
                ['name' => 'Dani Saputra', 'today_completed' => 2, 'today_active' => 0, 'availability_status' => 'bertugas'],
                ['name' => 'Rudi M.', 'today_completed' => 0, 'today_active' => 0, 'availability_status' => 'standby'],
            ]);
        }
    }

    public function dashboard()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $orderModel   = new PickupOrderModel();
        $courierModel = new CourierModel();

        $this->seedDemoData($orderModel, $courierModel);

        $orders = $orderModel->orderBy('id', 'DESC')->findAll();
        $couriers = $courierModel->orderBy('id', 'ASC')->findAll();

        $stats = [
            'total' => count($orders),
            'selesai' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'selesai')),
            'menunggu' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'menunggu')),
            'aktif' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'aktif')),
            'batal' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'batal')),
        ];

        $takenCount = count(array_filter($orders, static function (array $order): bool {
            if (! empty($order['assigned_mitra_id'])) {
                return true;
            }

            return in_array($order['status'], ['aktif', 'selesai'], true);
        }));

        $totalOrders = max(1, $stats['total']);
        $pickupProgress = [
            'taken' => $takenCount,
            'waiting' => max(0, $stats['total'] - $takenCount),
            'taken_pct' => (int) round(($takenCount / $totalOrders) * 100),
            'waiting_pct' => (int) round(((max(0, $stats['total'] - $takenCount)) / $totalOrders) * 100),
            'selesai_pct' => (int) round(($stats['selesai'] / $totalOrders) * 100),
            'aktif_pct' => (int) round(($stats['aktif'] / $totalOrders) * 100),
            'batal_pct' => (int) round(($stats['batal'] / $totalOrders) * 100),
        ];

        return view('admin/dashboard', [
            'orders'      => $orders,
            'couriers'    => $couriers,
            'stats'       => $stats,
            'pickupProgress' => $pickupProgress,
            'currentUser' => session()->get('user'),
            'message'     => session()->getFlashdata('message'),
            'errors'      => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function updateOrderStatus(int $id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/admin/dashboard')->with('errors', ['status' => 'Metode request tidak valid.']);
        }

        $allowedStatus = ['aktif', 'selesai', 'menunggu', 'batal'];
        $status = strtolower(trim((string) $this->request->getPost('status')));

        if (! in_array($status, $allowedStatus, true)) {
            return redirect()->to('/admin/dashboard')->with('errors', ['status' => 'Status tidak valid.']);
        }

        $orderModel = new PickupOrderModel();
        $order = $orderModel->find($id);

        if (! $order) {
            return redirect()->to('/admin/dashboard')->with('errors', ['status' => 'Data pesanan tidak ditemukan.']);
        }

        $payload = ['status' => $status];

        if ($status === 'menunggu' || $status === 'batal') {
            $payload['assigned_mitra_id'] = null;
        }

        $orderModel->update($id, $payload);

        return redirect()->to('/admin/dashboard')->with('message', 'Status pesanan berhasil diperbarui.');
    }

    public function deleteOrder(int $id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/admin/dashboard')->with('errors', ['delete' => 'Metode request tidak valid.']);
        }

        $orderModel = new PickupOrderModel();
        $order = $orderModel->find($id);

        if (! $order) {
            return redirect()->to('/admin/dashboard')->with('errors', ['delete' => 'Data pesanan tidak ditemukan.']);
        }

        $orderModel->delete($id);

        return redirect()->to('/admin/dashboard')->with('message', 'Data pesanan berhasil dihapus dari database.');
    }

    public function createAdmin()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[191]|is_unique[users.email]',
            'phone'     => 'required|regex_match[/^\\+?[0-9]{8,20}$/]',
            'password'  => 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[A-Za-z])(?=.*\\d).+$/]',
            'role'      => 'required|in_list[admin,mitra]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/dashboard')->withInput()->with('errors', $this->validator->getErrors());
        }

        $role = (string) $this->request->getPost('role');

        $userModel = new UserModel();
        $created   = $userModel->insert([
            'full_name'     => (string) $this->request->getPost('full_name'),
            'email'         => (string) $this->request->getPost('email'),
            'phone'         => (string) $this->request->getPost('phone'),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $role,
        ]);

        if (! $created) {
            return redirect()->to('/admin/dashboard')->with('errors', ['create_admin' => 'Gagal membuat akun internal.']);
        }

        $roleLabel = $role === 'mitra' ? 'Mitra' : 'Admin';

        return redirect()->to('/admin/dashboard')->with('message', $roleLabel . ' baru berhasil dibuat.');
    }
}
