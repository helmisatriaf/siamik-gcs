<?php

namespace App\Helpers;

use setasign\Fpdi\Fpdi;


class WatermarkPdf extends Fpdi
{
    var $angle = 0;

    function Header()
    {
        $this->SetFont('Arial', 'B', 50);
        $this->SetTextColor(128, 128, 128, 0.5);  // Warna merah
        $this->Rotate(45, 60, 190);
        $this->Text(30, 220, "Great Crystal School");
        $this->Rotate(0);
     }

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.2F %.2F %.2F %.2F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
}