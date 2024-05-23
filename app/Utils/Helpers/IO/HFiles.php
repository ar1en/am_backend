<?php


namespace App\Utils\Helpers\IO;


/**
 * Helper for files operations
 *
 * Вспомогательные функции для работы с файловой системой
 *
 * @package App\BPM\Utils\Helpers
 */
class HFiles {

    /** @var string формат даты для вывода списка файлов */
    public const C_DATE_FORMAT_F_D_Y_H_I_S = "F d Y H:i:s.";


    /**
     * Убирает путь и расширение, оставляеттолько имя файла
     *
     * @param string $name
     * @return string
     */
    public static function basenameWithoutExt($name) {
        $pos = strrpos($name, "/");
        if ($pos !== false) {
            $name = substr($name, $pos + 1);
        }
        $pos = strrpos($name, "\\");
        if ($pos !== false) {
            $name = substr($name, $pos + 1);
        }
        $pos = strrpos($name, ".");
        if ($pos !== false) {
            $name = substr($name, 0, $pos);
        }
        return $name;
    }

    /**
     * Remove any . at path.
     *
     * @param string $path
     * @return string
     */
    public static function clearPath(string $path) {
        $path = /*realpath*/
            (
            implode('/',
                array_map(
                    function ($value) {
                        return trim($value, '.');
                    },
                    explode('/', str_replace('\\', '/', $path)))
            )
            );
        return $path;
    }


    /**
     * Убирает из строки все не буквы, цифры, подчеркивания и тире,
     * все остальное заменяем на символ (по умолчанию тире)
     *
     * @param string $name
     * @return string|string[]|null
     */
    public static function filterFilename(string $name): string {
        return preg_replace('/[^a-z0-9_-]+/', '-', strtolower($name));
    }


    /**
     * Get list of files array of [
     *      'name'      => $entry,
     *      'ext'      => $ext,
     *      'path'      => $fullPath,
     *      'is_folder' => $isDir,
     *      'size'      => 0,
     *      'size_text' => 'D',
     *      'modified'  => date("F d Y H:i:s.", $modified),
     *      'level'      => $level,
     *      ];
     * @param string     $path
     * @param null|array $extensions
     * @param bool       $withFolders
     * @param bool       $recursive
     * @param int        $level
     * @return array
     */
    public static function list(string $path, ?array $extensions = null, $withFolders = false,
                                $recursive = false, $level = 1): array {

        // get files list
        $handle = opendir($path);
        if (!$handle) {
            return [];
        }

        // filter list
        $files = [];
        while (false !== ($entry = readdir($handle))) {

            // check dir self|up
            if (trim($entry, ".") === '') {
                continue;
            }
            $fullPath = $path . '/' . $entry;
            $ext      = self::getExtension($entry);
            $modified = filemtime($fullPath);

            // check dir
            $isDir = is_dir($fullPath);
            if ($isDir) {
                // add folder if not ignored
                if ($withFolders) {
                    $files[] = [
                        'name'      => $entry,
                        'ext'       => $ext,
                        'path'      => $fullPath,
                        'is_folder' => $isDir,
                        'size'      => 0,
                        'size_text' => 'D',
                        'modified'  => date(self::C_DATE_FORMAT_F_D_Y_H_I_S, $modified),
                        'level'     => $level,
                    ];
                }
                if ($recursive) {
                    $subs  = self::list($path . '/' . $entry, $extensions, $withFolders, $recursive, $level + 1);
                    $files = array_merge($files, $subs);
                }
                continue;
            }

            // check extension
            if (is_array($extensions) && array_search($ext, $extensions) === false) {
                continue;
            }

            // add entry
            $size    = filesize($fullPath);
            $files[] = [
                'name'      => $entry,
                'ext'       => $ext,
                'path'      => $fullPath,
                'is_folder' => $isDir,
                'size'      => $size,
                'size_text' => self::convertToReadableSize($size),
                'modified'  => date(self::C_DATE_FORMAT_F_D_Y_H_I_S, $modified),
                'level'     => $level,
            ];

        }

        closedir($handle);

        return $files;

    }

    /**
     * Возвращает список файлов в каталоге в хранилище storage
     * @param string $pathAtStorage
     * @param null   $filterFileExtension - divided by "," like "pdf,doc,txt" or array = ['pdf','doc','txt']
     * @param string $storageType         - тип хранилища storage (default) | public | null (system path)
     * @return array
     */
    public static function getDirList($pathAtStorage,
                                      $filterFileExtension = null,
                                      $storageType = 'storage') {

        // define extension filters
        if ($filterFileExtension) {
            if (!is_array($filterFileExtension)) {
                $filterFileExtension = explode(',', $filterFileExtension);
            }
            foreach ($filterFileExtension as $i => $v) {
                $filterFileExtension[$i] = '.' . strtolower($v);
            }
        }

        // define path (public or storage)
        $files    = [];
        $filePath = null;
        if ($storageType === null) {
            $filePath = $pathAtStorage;
        }
        if ($storageType === 'storage') {
            $filePath = storage_path($pathAtStorage);
        }
        if ($storageType === 'public') {
            $filePath = public_path($pathAtStorage);
        }
        if ($filePath === null) {
            return [];
        }

        // get files list
        $handle = opendir($filePath);
        if (!$handle) {
            return $files;
        }

        // filter list
        while (false !== ($entry = readdir($handle))) {
            if ($entry === "." || $entry === "..") {
                continue;
            }
            if ($filterFileExtension == null) {
                $files[] = $entry;
                continue;
            }
            foreach ($filterFileExtension as $v) {
                if (str_ends_with(strtolower($entry), $v)) {
                    $files[] = $entry;
                    break;
                }
            }
        }

        closedir($handle);

        arsort($files);

        return $files;
    }


    /**
     * Возвращает список файлов
     *
     * @param        $pathAtStorage
     * @param null   $filterFileExtension
     * @param string $storageType
     * @return array - list of files [name,size,modified]
     */
    public static function getDirListInfo($pathAtStorage, $filterFileExtension = null, $storageType = 'storage') {

        if ($storageType !== 'public' && $storageType !== 'storage') {
            return [];
        }

        $fileNames = self::getDirList($pathAtStorage, $filterFileExtension, $storageType);
        $filePath  = $storageType === 'public' ? public_path($pathAtStorage) : storage_path($pathAtStorage);
        $files     = [];
        foreach ($fileNames as $name) {
            $fn = $filePath . $name;
            if (file_exists($fn)) {
                $size     = filesize($fn);
                $modified = filemtime($fn);
                if (false === $size || !$modified) {
                    continue;
                }
                $files[] = [
                    'name'     => $name,
                    'size'     => self::convertToReadableSize($size),
                    'modified' => date(self::C_DATE_FORMAT_F_D_Y_H_I_S, $modified),
                ];
            }
        }
        return $files;
    }

    /**
     * Convert bytes count to readable text.
     * @param $size
     * @return string
     */
    public static function convertToReadableSize($size) {
        $base   = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }

    /**
     * Преобразцет файловую дату в читаемый формат
     *
     * @param $time
     * @return false|string
     */
    public static function convertToReadableTime($time) {
        return date("Y-m-d H:i:s.", $time);
    }

    /**
     * Возвращает строку  информации о файле
     * @param string $fileName
     * @return string
     */
    public static function fileInfoString(string $fileName) {
        if (file_exists($fileName)) {
            $fs  = self::convertToReadableSize(filesize($fileName));
            $ftc = self::convertToReadableTime(filectime($fileName));
            $ftm = self::convertToReadableTime(filemtime($fileName));
            $fnb = basename($fileName);
            return "File: {$fnb} ({$fs}) Created: {$ftc} Modified: {$ftm}";
        } else {
            return "File not exists: {$fileName}";
        }

    }

    /**
     * Remove all files of directory.
     *
     * @param string $sysPath
     */
    public static function directoryClearFiles(string $sysPath) {

        $files = glob($sysPath . '/*'); //get all file names
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); //delete file
            }
        }

    }

    /**
     * Возвращает расширекние файла.
     *
     * @param string $path
     * @return false|string
     */
    public static function getExtension($path) {
        $pos = strrpos($path, '.');
        if ($pos === false) {
            return '';
        }
        return substr($path, $pos + 1);
    }

    /**
     * Отправляем данные файла пользователю.
     * Добавляет в ответ тип контента, чистит буфер перед отправкой.
     *
     * @param string      $path
     * @param string|null $filename
     * @param bool        $saveExtension
     * @return bool
     */
    public static function sendFile($path, $filename = null, $saveExtension = true) {

        if (!file_exists($path)) {
            return false;
        }

        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        // если этого не сделать файл будет читаться в память полностью!
        if (ob_get_level()) {
            ob_end_clean();
        }

        if ($filename == null) {
            $filename = basename($path);
        }

        // сохраняем в итоговом имени файла для клиента расширение
        $ext = self::getExtension($path);
        if ($saveExtension && !str_ends_with($filename, '.' . $ext)) {
            $filename .= '.' . $ext;
        }

        // заставляем браузер показать окно сохранения файла
        $contentType = 'application/octet-stream';//\mime_content_type($path);
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));

        // читаем файл и отправляем его пользователю
        readfile($path);

        return true;

    }


}
