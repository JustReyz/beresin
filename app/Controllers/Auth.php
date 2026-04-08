<?php

namespace App\Controllers;

use App\Models\PickupOrderModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    private function redirectByRole(string $role)
    {
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($role === 'mitra') {
            return redirect()->to('/mitra/dashboard');
        }

        return redirect()->to('/dashboard');
    }

    private function viewData(string $initialView): array
    {
        return [
            'initialView' => $initialView,
            'errors'      => session()->getFlashdata('errors') ?? [],
            'message'     => session()->getFlashdata('message'),
            'currentUser' => session()->get('user'),
        ];
    }

    public function login()
    {
        $sessionUser = session()->get('user');
        if ($sessionUser) {
            return $this->redirectByRole((string) ($sessionUser['role'] ?? 'user'));
        }

        return view('app_shell', $this->viewData('login'));
    }

    public function register()
    {
        $sessionUser = session()->get('user');
        if ($sessionUser) {
            return $this->redirectByRole((string) ($sessionUser['role'] ?? 'user'));
        }

        return view('app_shell', $this->viewData('register'));
    }

    public function loginSubmit()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]|max_length[72]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user      = $userModel->where('email', (string) $this->request->getPost('email'))->first();

        if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password_hash'])) {
            return redirect()->back()->withInput()->with('errors', ['login' => 'Email atau password tidak sesuai.']);
        }

        session()->set('user', [
            'id'    => $user['id'],
            'name'  => $user['full_name'],
            'email' => $user['email'],
            'role'  => $user['role'] ?? 'user',
        ]);

        return $this->redirectByRole((string) ($user['role'] ?? 'user'));
    }

    public function registerSubmit()
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[191]|is_unique[users.email]',
            'phone'     => 'required|regex_match[/^\\+?[0-9]{8,20}$/]',
            'password'  => 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[A-Za-z])(?=.*\\d).+$/]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $saved     = $userModel->insert([
            'full_name'     => (string) $this->request->getPost('full_name'),
            'email'         => (string) $this->request->getPost('email'),
            'phone'         => (string) $this->request->getPost('phone'),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => 'user',
        ]);

        if (! $saved) {
            return redirect()->back()->withInput()->with('errors', ['register' => 'Gagal menyimpan data pengguna.']);
        }

        return redirect()->to('/login')->with('message', 'Registrasi berhasil. Silakan login.');
    }

    public function dashboard()
    {
        $sessionUser = session()->get('user');
        if (! $sessionUser) {
            return redirect()->to('/login')->with('errors', ['login' => 'Silakan login terlebih dahulu.']);
        }

        $role = (string) ($sessionUser['role'] ?? 'user');
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($role === 'mitra') {
            return redirect()->to('/mitra/dashboard');
        }

        $orderModel = new PickupOrderModel();

        $orders = $orderModel
            ->select('pickup_orders.*, users.full_name as mitra_name')
            ->join('users', 'users.id = pickup_orders.assigned_mitra_id', 'left')
            ->where('pickup_orders.user_id', (int) ($sessionUser['id'] ?? 0))
            ->orderBy('pickup_orders.id', 'DESC')
            ->findAll();

        $activeOrder = null;
        foreach ($orders as $order) {
            if (in_array($order['status'], ['menunggu', 'aktif'], true)) {
                $activeOrder = $order;
                break;
            }
        }

        $stats = [
            'total' => count($orders),
            'menunggu' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'menunggu')),
            'aktif' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'aktif')),
            'selesai' => count(array_filter($orders, static fn(array $order): bool => $order['status'] === 'selesai')),
        ];

        $historyOrders = array_values(array_filter($orders, static fn(array $order): bool => in_array($order['status'], ['selesai', 'batal'], true)));

        return view('app_shell', array_merge($this->viewData('dashboard'), [
            'orders' => $orders,
            'activeOrder' => $activeOrder,
            'stats' => $stats,
            'historyOrders' => $historyOrders,
        ]));
    }

    public function logout()
    {
        session()->remove('user');

        return redirect()->to('/login')->with('message', 'Anda berhasil logout.');
    }

    public function updatePassword()
    {
        $sessionUser = session()->get('user');
        if (! $sessionUser) {
            return redirect()->to('/login')->with('errors', ['password_update' => 'Silakan login terlebih dahulu.']);
        }

        if (! $this->request->is('post')) {
            return redirect()->back()->with('errors', ['password_update' => 'Metode request tidak valid.']);
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[A-Za-z])(?=.*\d).+$/]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find((int) ($sessionUser['id'] ?? 0));

        if (! $user || ! password_verify((string) $this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->back()->withInput()->with('errors', ['password_update' => 'Password saat ini tidak sesuai.']);
        }

        $userModel->update((int) $user['id'], [
            'password_hash' => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);

        return $this->redirectByRole((string) ($sessionUser['role'] ?? 'user'))->with('message', 'Password berhasil diperbarui.');
    }
}
