<?php


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone;
use Endroid\QrCode\Color\Color;

class QrCodeController extends AdminController {
    
    public function generate_qr_code($data = null) {
        $data = $data ?? "I love programming";
        
        if (empty($data)) {
            echo 'No data provided for QR code generation';
            return;
        }

        try {
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($data)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(new RoundBlockSizeModeNone())
                ->backgroundColor(new Color(255, 255, 255))
                ->build();

            header('Content-Type: ' . $result->getMimeType());
            echo $result->getString();
        } catch (\Exception $e) {
            echo 'QR Code generation failed: ' . $e->getMessage();
        }
    }
}
