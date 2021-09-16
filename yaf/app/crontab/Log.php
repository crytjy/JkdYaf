<?php

namespace app\crontab;

use Common\DeleteFile;

class Log
{
    public function delLog()
    {
        DeleteFile::delLogs();
    }
}