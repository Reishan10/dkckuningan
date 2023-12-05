<?php

namespace App\Http\Controllers;

use Ilovepdf\Ilovepdf as LZW;

class FileCompressor extends Controller
{
    public static function compressFile($filePath)
    {
        $lzw = new LZW('project_public_d09d93d43d6c50b22a77448f6b3c1a96_CHJ-x909810da60baf4deb64175a136f3bd6a', 'secret_key_fa6f4b36e077abb9a359ac0e5315cc27_F6fu7fb53e2975d4a141aa2521f0e1ad331a5');
        $myTask = $lzw->newTask('compress');
        $myTask->addFile($filePath);
        $myTask->execute();
        $myTask->download(public_path('berkas'));

        return filesize($filePath);
    }
}
