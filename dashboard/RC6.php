<?php
class RC6 {
    private $r = 20; 
    private $S = []; 
    private $P32 = 0xB7E15163;
    private $Q32 = 0x9E3779B9;

    public function __construct($key) {
        $this->keyExpansion($key);
    }

    private function rotateLeft($val, $shift) {
        $val = (int)$val & 0xFFFFFFFF;
        $shift %= 32;
        return (($val << $shift) | ($val >> (32 - $shift))) & 0xFFFFFFFF;
    }

    private function rotateRight($val, $shift) {
        $val = (int)$val & 0xFFFFFFFF;
        $shift %= 32;
        return (($val >> $shift) | ($val << (32 - $shift))) & 0xFFFFFFFF;
    }

    private function keyExpansion($key) {
        $L = array_values(unpack('V*', str_pad($key, 16, "\0")));
        $c = count($L);
        $t = 44; 
        $this->S = array_fill(0, $t, 0);
        $this->S[0] = $this->P32;
        for ($i = 1; $i < $t; $i++) {
            $this->S[$i] = ($this->S[$i - 1] + $this->Q32) & 0xFFFFFFFF;
        }
        $i = $j = $A = $B = 0;
        $v = 3 * max($t, $c);
        for ($k = 0; $k < $v; $k++) {
            $A = $this->S[$i] = $this->rotateLeft(($this->S[$i] + $A + $B), 3);
            $B = $L[$j] = $this->rotateLeft(($L[$j] + $A + $B), ($A + $B));
            $i = ($i + 1) % $t;
            $j = ($j + 1) % $c;
        }
    }

    public function encrypt($data) {
        $words = array_values(unpack('V4', $data));
        $A = $words[0]; $B = $words[1]; $C = $words[2]; $D = $words[3];
        $B = ($B + $this->S[0]) & 0xFFFFFFFF;
        $D = ($D + $this->S[1]) & 0xFFFFFFFF;
        for ($i = 1; $i <= $this->r; $i++) {
            $t_val = $this->rotateLeft((($B * (2 * $B + 1)) & 0xFFFFFFFF), 5);
            $u_val = $this->rotateLeft((($D * (2 * $D + 1)) & 0xFFFFFFFF), 5);
            $A = ($this->rotateLeft(($A ^ $t_val), $u_val) + $this->S[2 * $i]) & 0xFFFFFFFF;
            $C = ($this->rotateLeft(($C ^ $u_val), $t_val) + $this->S[2 * $i + 1]) & 0xFFFFFFFF;
            $temp = $A; $A = $B; $B = $C; $C = $D; $D = $temp;
        }
        $A = ($A + $this->S[42]) & 0xFFFFFFFF;
        $C = ($C + $this->S[43]) & 0xFFFFFFFF;
        return pack('V4', $A, $B, $C, $D);
    }

    public function decrypt($data) {
        $words = array_values(unpack('V4', $data));
        $A = $words[0]; $B = $words[1]; $C = $words[2]; $D = $words[3];
        $C = ($C - $this->S[43]) & 0xFFFFFFFF;
        $A = ($A - $this->S[42]) & 0xFFFFFFFF;
        for ($i = $this->r; $i >= 1; $i--) {
            $temp = $D; $D = $C; $C = $B; $B = $A; $A = $temp;
            $u_val = $this->rotateLeft((($D * (2 * $D + 1)) & 0xFFFFFFFF), 5);
            $t_val = $this->rotateLeft((($B * (2 * $B + 1)) & 0xFFFFFFFF), 5);
            $C = ($this->rotateRight(($C - $this->S[2 * $i + 1]), $t_val) ^ $u_val) & 0xFFFFFFFF;
            $A = ($this->rotateRight(($A - $this->S[2 * $i]), $u_val) ^ $t_val) & 0xFFFFFFFF;
        }
        $D = ($D - $this->S[1]) & 0xFFFFFFFF;
        $B = ($B - $this->S[0]) & 0xFFFFFFFF;
        return pack('V4', $A, $B, $C, $D);
    }
}