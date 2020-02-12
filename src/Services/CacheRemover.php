<?php


namespace Services;


class CacheRemover
{
    public function clearCache()
    {
        $cacheDirectories = array_diff(scandir($GLOBALS['cacheDirectory']), array('..', '.'));

        $unsetKey = array_search('.gitignore',$cacheDirectories);
        unset($cacheDirectories[$unsetKey]);

        foreach ($cacheDirectories as $cacheDirectory)
        {
            $cacheFiles = array_diff(scandir($GLOBALS['cacheDirectory'].$cacheDirectory), array('..', '.'));

            foreach ($cacheFiles as $cacheFile)
            {
                unlink($GLOBALS['cacheDirectory'].$cacheDirectory.'/'.$cacheFile);
            }

            rmdir($GLOBALS['cacheDirectory'].$cacheDirectory);
        }
    }
}