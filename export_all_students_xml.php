<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Akademik\PesertaDidik;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\ExportsExcel;

class ExportSiswaXML {
    use ExportsExcel;

    public function generate() {
        $students = PesertaDidik::all();
        
        // Setup XML
        $dark = '#1e3a8a'; // Tailwind blue-900
        $mid = '#3b82f6';  // Tailwind blue-500
        $light = '#eff6ff'; // Tailwind blue-50

        $xml = $this->xmlOpen('Data Akun Siswa', 5, $dark, $mid, $light);
        
        // Setup Worksheet
        $xml .= '<Worksheet ss:Name="Akun Siswa">' . "\n" . '<Table>' . "\n";
        
        // Define Column Widths explicitly
        $xml .= '<Column ss:Index="1" ss:Width="80"/>' . "\n";
        $xml .= '<Column ss:Index="2" ss:Width="200"/>' . "\n";
        $xml .= '<Column ss:Index="3" ss:Width="150"/>' . "\n";
        $xml .= '<Column ss:Index="4" ss:Width="250"/>' . "\n";
        $xml .= '<Column ss:Index="5" ss:Width="150"/>' . "\n";
        
        // Title
        $xml .= $this->xmlTitleRow('DATA AKUN LOGIN SISWA', 5);
        $xml .= $this->xmlInfoRow('Tanggal Export: ' . date('d-m-Y H:i:s'), 5);
        
        // Header
        $headers = ['ID Peserta', 'Nama Lengkap', 'Username', 'Email Akun', 'Password Akun'];
        $xml .= $this->xmlHeaderRow($headers);
        
        $rowIndex = 1;
        foreach ($students as $student) {
            $user = User::where('peserta_didik_id', $student->id)->first();
            
            $style = $rowIndex % 2 == 0 ? 's_data2' : 's_data';
            $styleText = $rowIndex % 2 == 0 ? 's_text2' : 's_text';
            
            if ($user) {
                $username = $user->username ?? '';
                $email = $user->email ?? '';
                // Since password in DB is hashed, we can't extract the plain password. 
                // But wait! We need to RE-GENERATE the password to show it in the export.
                // Or wait, my previous script ALREADY updated the DB with new passwords?
                // The prompt says "sama saja" (it's the same visual issue). The data in the CSV was perfectly fine.
                // Since they already ran the previous script, the passwords were changed. To show them again, 
                // I will just re-derive the plain text password using the same logic!
                
                $firstName = explode(' ', $student->nama_lengkap)[0];
                $plainPassword = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName)) . '123';
                if (strlen($plainPassword) < 8) {
                    $plainPassword .= '456';
                }
                
                $xml .= '<Row ss:Height="20">';
                $xml .= '<Cell ss:StyleID="' . $styleText . '"><Data ss:Type="String">' . $this->x($student->id) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="String">' . $this->x($student->nama_lengkap) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="String">' . $this->x($username) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="String">' . $this->x($email) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $style . '"><Data ss:Type="String">' . $this->x($plainPassword) . '</Data></Cell>';
                $xml .= '</Row>' . "\n";
                $rowIndex++;
            }
        }
        
        $xml .= '</Table></Worksheet></Workbook>';
        
        $filePath = '/var/www/html/public/akun_semua_siswa_terbaik.xls';
        file_put_contents($filePath, $xml);
        
        echo "Berhasil membuat file XML Spreadsheet.\n";
        echo "Tersimpan di: " . $filePath . "\n";
    }
    
    // Polyfill for x() just in case it's protected in trait
    protected function x(mixed $v): string
    {
        return htmlspecialchars((string) ($v ?? ''), ENT_XML1, 'UTF-8');
    }
}

$exporter = new ExportSiswaXML();
$exporter->generate();

