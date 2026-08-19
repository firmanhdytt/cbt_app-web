<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CbtAppTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest is redirected to login.
     */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test admin dashboard access.
     */
    public function test_admin_can_access_admin_dashboard()
    {
        // Create admin user
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    /**
     * Test student dashboard access.
     */
    public function test_siswa_can_access_siswa_dashboard()
    {
        // Create student user
        $siswa = User::factory()->create([
            'role' => 'siswa',
            'nis' => '12345',
        ]);

        $response = $this->actingAs($siswa)->get('/siswa/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Siswa');
    }

    /**
     * Test role redirect logic.
     */
    public function test_dashboard_redirects_by_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $siswa = User::factory()->create(['role' => 'siswa', 'nis' => '12345']);

        $responseAdmin = $this->actingAs($admin)->get('/dashboard');
        $responseAdmin->assertRedirect(route('admin.dashboard'));

        $responseSiswa = $this->actingAs($siswa)->get('/dashboard');
        $responseSiswa->assertRedirect(route('siswa.dashboard'));
    }

    /**
     * Test admin mapel CRUD.
     */
    public function test_admin_can_create_mapel()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/mapel', [
            'nama_mapel' => 'Bahasa Inggris',
            'kode_mapel' => 'BIG',
        ]);

        $response->assertRedirect(route('admin.mapel.index'));
        $this->assertDatabaseHas('mapels', [
            'kode_mapel' => 'BIG',
            'nama_mapel' => 'Bahasa Inggris',
        ]);
    }
}
