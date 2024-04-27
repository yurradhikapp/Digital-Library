<?php 
use Dompdf\Dompdf;
class PeminjamController extends Controller
{
  public function __construct()
  {
    /**
      * Batasi hak akses hanya untuk Administrator dan Petugas
      * Selain Administrator dan Petugas akan langsung diarahkan kembali ke halaman home
    */
    if ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Petugas') {
      redirectTo('error', 'Mohon maaf, Anda tidak berhak mengakses halaman ini', '/');
    }
  }

  public function index()
  {
    $data = $this->model('Peminjaman')->get();
    $this->view('peminjam/home', $data);
  }
  public function cetakpeminjam()
  {
    $data = $this->model('Peminjaman')->get();
    $html 	= "<center>";
		$html 	.= "<h1>Digital Library</h1>";
		$html 	.= "<h3>DAFTAR PEMINJAM</h3>";
		$html 	.= "<hr>";
    $html   .= "<table align='center' border='1' cellpadding='10' cellspacing='0'>";
		$html   .= "<tr><th>#</th><th>Nama Peminjam</th><th>Alamat Peminjam</th><th>Buku Yang Dipinjam</th><th>Tanggal Dikembalikan</th></tr>";
    $no = 1;
    foreach ($data as $buku) {
      $html .= "<tr>";
      $html .= "<td>".$no."</td>";
      $html .= "<td>".$buku['NamaLengkap']."</td>";
      $html .= "<td>".$buku['Alamat']."</td>";
      $html .= "<td>".$buku['Judul']."</td>";
      $html .= "<td>".$buku['TanggalPengembalian']."</td>";
      $html .= "</tr>";
      $no++;
    }
    $html   .= "</table>";
    $html 	.= "</center>";
    $dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('4A', 'potrait');
		$dompdf->render();
		$dompdf->stream('Data Peminjam', ['Attachment' => 0]);
  }
}
