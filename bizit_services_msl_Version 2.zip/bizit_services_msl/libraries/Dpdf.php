<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . "/dompdf/autoload.inc.php";

use Dompdf\Dompdf;

class Dpdf extends Dompdf{ 
    public function __construct()
    {
        parent::__construct();
        $this->set_option('isRemoteEnabled', true);  // Enable remote resources globally
    }

    public function pdf_create($html, $filename = '', $stream = '')
    {
        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();

        if ($stream == 'view') {
            return $dompdf->stream($filename, array("Attachment" => false));
        } else if ($stream == 'download') {
            return $dompdf->stream($filename);
        } else if ($stream == 'send_email') {
            return $dompdf->output();
        }
    }

    // Use this method to apply multiple options at once
    public function set_options(array $options)
    {
        foreach ($options as $key => $value) {
            $this->set_option($key, $value);
        }
        return $this;
    }
  public function pdf_create_landscape($html, $filename='', $stream='') 
   {
    $dompdf = new Dompdf();
    $dompdf->setPaper('letter', 'landscape');
    $dompdf->loadHtml($html);
    $dompdf->render();
    if ($stream == 'view') {
        return $dompdf->stream($filename, array("Attachment" => false));
    }else if ($stream == 'download') {
        return $dompdf->stream($filename);
    }else if($stream == 'send_email') {
        return $dompdf->output();
    }
  }
}
