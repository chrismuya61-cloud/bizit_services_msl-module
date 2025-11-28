<?php

class QrCode extends MX_Controller {
    
    public function generate($data) {
        // Your QR code generation logic
        // Ensure $data is used correctly here

        $qrCode = Builder::create()
            ->data($data)
            ->build();

        header('Content-Type: '.$qrCode->getMimeType());
        echo $qrCode->getString();
    }
}

