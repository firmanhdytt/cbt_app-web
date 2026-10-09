<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\GuruKelasMapel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Spatie\Permission\Models\Role;

class TeacherWorkspaceRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('admin');
        Role::findOrCreate('guru');
        Role::findOrCreate('siswa');
    }

    public function test_admin_can_assign_teacher_to_class_and_subject()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru  = User::factory()->create(['role' => 'guru']);
        $kelas = Kelas::create(['nama_kelas' => 'XII-A', 'tingkat' => '12']);
        $mapel = Mapel::create(['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK']);

        $response = $this->actingAs($admin)->put(route('admin.pengguna.update', $guru->id), [
            'name'     => $guru->name,
            'username' => $guru->username,
            'email'    => $guru->email,
            'role'     => 'guru',
            'penempatan' => [
                ['kelas_id' => $kelas->id, 'mapel_id' => $mapel->id]
            ]
        ]);

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseHas('guru_kelas_mapel', [
            'user_id'  => $guru->id,
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
        ]);
        $this->assertTrue($guru->fresh()->hasWorkspaceAccess($kelas->id, $mapel->id));
    }

    public function test_teacher_cannot_create_bank_soal_outside_workspace()
    {
        $guru  = User::factory()->create(['role' => 'guru']);
        $kelas1 = Kelas::create(['nama_kelas' => 'XII-A']);
        $mapel1 = Mapel::create(['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK']);
        
        $kelas2 = Kelas::create(['nama_kelas' => 'XII-B']);
        $mapel2 = Mapel::create(['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BIN']);

        // Assign Guru to XII-A Matematika ONLY
        GuruKelasMapel::create([
            'user_id'  => $guru->id,
            'kelas_id' => $kelas1->id,
            'mapel_id' => $mapel1->id,
        ]);

        // Attempt store question for assigned workspace -> Success
        $res1 = $this->actingAs($guru)->post(route('guru.soal.store'), [
            'kelas_id'      => $kelas1->id,
            'mapel_id'      => $mapel1->id,
            'pertanyaan'    => 'Berapakah 1 + 1?',
            'pilihan_a'     => '2',
            'pilihan_b'     => '3',
            'pilihan_c'     => '4',
            'pilihan_d'     => '5',
            'jawaban_benar' => 'A',
        ]);
        $res1->assertRedirect();
        $this->assertDatabaseHas('soals', ['pertanyaan' => 'Berapakah 1 + 1?']);

        // Attempt store question for unassigned workspace -> 403 Forbidden
        $res2 = $this->actingAs($guru)->post(route('guru.soal.store'), [
            'kelas_id'      => $kelas2->id,
            'mapel_id'      => $mapel2->id,
            'pertanyaan'    => 'Soal Bahasa Indonesia?',
            'pilihan_a'     => 'A',
            'pilihan_b'     => 'B',
            'pilihan_c'     => 'C',
            'pilihan_d'     => 'D',
            'jawaban_benar' => 'A',
        ]);
        $res2->assertStatus(403);
    }

    public function test_teacher_cannot_create_exam_outside_workspace_or_pick_unauthorized_soal()
    {
        $guru  = User::factory()->create(['role' => 'guru']);
        $kelas1 = Kelas::create(['nama_kelas' => 'XII-A']);
        $mapel1 = Mapel::create(['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK']);

        $kelas2 = Kelas::create(['nama_kelas' => 'XII-B']);
        $mapel2 = Mapel::create(['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BIN']);

        GuruKelasMapel::create([
            'user_id'  => $guru->id,
            'kelas_id' => $kelas1->id,
            'mapel_id' => $mapel1->id,
        ]);

        $soalValid = Soal::create([
            'kelas_id'      => $kelas1->id,
            'mapel_id'      => $mapel1->id,
            'created_by'    => $guru->id,
            'pertanyaan'    => 'Soal Valid',
            'pilihan_a'     => 'A',
            'pilihan_b'     => 'B',
            'pilihan_c'     => 'C',
            'pilihan_d'     => 'D',
            'jawaban_benar' => 'A',
        ]);

        $otherGuru = User::factory()->create(['role' => 'guru']);
        $soalInvalid = Soal::create([
            'kelas_id'      => $kelas2->id,
            'mapel_id'      => $mapel2->id,
            'created_by'    => $otherGuru->id,
            'pertanyaan'    => 'Soal Invalid',
            'pilihan_a'     => 'A',
            'pilihan_b'     => 'B',
            'pilihan_c'     => 'C',
            'pilihan_d'     => 'D',
            'jawaban_benar' => 'A',
        ]);

        // Attempt creating exam in unassigned class -> 403 Forbidden
        $resForbidden = $this->actingAs($guru)->post(route('guru.ujian.store'), [
            'kelas_id'        => $kelas2->id,
            'mapel_id'        => $mapel2->id,
            'judul'           => 'Ujian XII-B',
            'durasi_menit'    => 60,
            'tanggal_mulai'   => now()->toDateTimeString(),
            'tanggal_selesai' => now()->addHours(2)->toDateTimeString(),
            'soals'           => [$soalInvalid->id],
        ]);
        $resForbidden->assertStatus(403);

        // Attempt creating exam in assigned class with invalid soal -> Validation error redirect
        $resInvalidSoal = $this->actingAs($guru)->post(route('guru.ujian.store'), [
            'kelas_id'        => $kelas1->id,
            'mapel_id'        => $mapel1->id,
            'judul'           => 'Ujian XII-A',
            'durasi_menit'    => 60,
            'tanggal_mulai'   => now()->toDateTimeString(),
            'tanggal_selesai' => now()->addHours(2)->toDateTimeString(),
            'soals'           => [$soalInvalid->id],
        ]);
        $resInvalidSoal->assertSessionHasErrors('soals');

        // Valid exam creation -> Success
        $resValid = $this->actingAs($guru)->post(route('guru.ujian.store'), [
            'kelas_id'        => $kelas1->id,
            'mapel_id'        => $mapel1->id,
            'judul'           => 'Ujian XII-A Valid',
            'durasi_menit'    => 60,
            'tanggal_mulai'   => now()->toDateTimeString(),
            'tanggal_selesai' => now()->addHours(2)->toDateTimeString(),
            'soals'           => [$soalValid->id],
        ]);
        $resValid->assertRedirect();
        $this->assertDatabaseHas('ujians', ['judul' => 'Ujian XII-A Valid']);
    }
}
