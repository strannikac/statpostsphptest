<?php
function autoload($className) {
    $className = ltrim($className, '\\');

    $arr = explode('\\', $className);
    $count = count( $arr );
    $fileName = '';

    if( $count > 0 ) {
        if( $count == 1 ) {
            //root class
            $fileName .= $arr[0] . '.php';
        } else {
            for( $i = 0; $i < $count; $i++ ) {
                if( $i > 0 ) {
                    $fileName .= DIRECTORY_SEPARATOR;
                }
                $fileName .= $arr[$i];
            }

            $fileName .= '.php';
        }
    } else {
        echo 'No class!'; exit;
    }

    if( !checkFile( $fileName ) ) {
        $fileName = $arr[0] . DIRECTORY_SEPARATOR . $fileName;
        if( !checkFile( $fileName ) ) {
            echo 'No file!';
        }
    }
}

function checkFile( $file ) {
    if (file_exists($file)) {
        require $file;
        return true;
    } elseif (file_exists(ROOTDIR . $file)) {
        require ROOTDIR . $file;
        return true;
    }

    return false;
}

spl_autoload_register('autoload');
?>