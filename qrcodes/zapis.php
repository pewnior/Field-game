<?php

class QrCode {
    private $adres_api = 'https://api.qrserver.com/v1/create-qr-code/';
    private $dane;

    public function url($adres_url = null) {
        $this->dane = preg_match("#^https?\:\/\/#", $adres_url)
            ? $adres_url
            : "http://{$adres_url}";
    }

    public function tekst($tresc) {
        $this->dane = $tresc;
    }

    public function email($adres_email = null, $temat = null, $tresc_wiadomosci = null) {
        $this->dane = "MATMSG:TO:{$adres_email};SUB:{$temat};BODY:{$tresc_wiadomosci};;";
    }

    public function telefon($numer_telefonu) {
        $this->dane = "TEL:{$numer_telefonu}";
    }

    public function sms($numer_telefonu = null, $tresc_sms = null) {
        $this->dane = "SMSTO:{$numer_telefonu}:{$tresc_sms}";
    }

    public function kontakt($imie_nazwisko = null, $adres = null, $numer_telefonu = null, $adres_email = null) {
        $this->dane = "MECARD:N:{$imie_nazwisko};ADR:{$adres};TEL:{$numer_telefonu};EMAIL:{$adres_email};;";
    }

    public function QRCODE($rozmiar = 400, $nazwa_pliku = null) {
        // Nowe API: qrserver.com — darmowe, aktywne, bez klucza
        $url = $this->adres_api . '?size=' . $rozmiar . 'x' . $rozmiar
             . '&data=' . urlencode($this->dane)
             . '&format=png&ecc=M';

        $sesja_curl = curl_init();
        curl_setopt($sesja_curl, CURLOPT_URL, $url);
        curl_setopt($sesja_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($sesja_curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($sesja_curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($sesja_curl, CURLOPT_SSL_VERIFYPEER, true);
        $obraz = curl_exec($sesja_curl);
        $kod_http = curl_getinfo($sesja_curl, CURLINFO_HTTP_CODE);
        curl_close($sesja_curl);

        if (!$obraz || $kod_http !== 200) {
            error_log("Blad generowania QR dla: {$this->dane}");
            return false;
        }

        if ($nazwa_pliku) {
            if (!preg_match("#\.png$#i", $nazwa_pliku)) {
                $nazwa_pliku .= '.png';
            }
            return file_put_contents($nazwa_pliku, $obraz);
        } else {
            header('Content-type: image/png');
            print $obraz;
            return true;
        }
    }
}
?>
