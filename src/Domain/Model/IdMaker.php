<?php declare(strict_types=1);


namespace App\Domain\Model;


trait IdMaker
{
    public function makeId(): string
    {
        return strtr(substr(base64_encode(md5(uniqid(), true)), 0, 6), '+/', '-_');
    }
}