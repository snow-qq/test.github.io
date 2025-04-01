<?php

namespace lab7;

class FileProcessor {
    private $currentDirectory;
    private $logFile;
    private $indentLevel = 0;
    
    public function __construct() {
        $this->currentDirectory = __DIR__ . '/../test_files';
        $this->logFile = __DIR__ . '/../processing.log';
        file_put_contents($this->logFile, "\n" . str_repeat('=', 50) . "\n");
        file_put_contents($this->logFile, "Начало обработки: " . date('Y-m-d H:i:s') . "\n");
        file_put_contents($this->logFile, str_repeat('=', 50) . "\n\n");
        
        if (!is_dir($this->currentDirectory)) {
            $this->log("Директория test_files не найдена!", 'error');
            exit;
        }
    }
    
    private function log($message, $type = 'info') {
        $indent = str_repeat('  ', $this->indentLevel);
        $prefix = '';
        
        switch ($type) {
            case 'dir':
                $prefix = '📁 ';
                break;
            case 'file':
                $prefix = '📄 ';
                break;
            case 'warning':
                $prefix = '⚠️ ';
                break;
            case 'success':
                $prefix = '✅ ';
                break;
            case 'error':
                $prefix = '❌ ';
                break;
            default:
                $prefix = 'ℹ️ ';
        }
        
        $logMessage = date('Y-m-d H:i:s') . " | " . $indent . $prefix . $message . "\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        echo $logMessage;
    }
    
    public function processFiles() {
        $this->log("Начинаем сканирование директории: " . $this->currentDirectory, 'dir');
        $this->scanDirectory($this->currentDirectory);
        $this->log("\n" . str_repeat('=', 50) . "\n");
        $this->log("Завершение обработки", 'success');
        $this->log(str_repeat('=', 50) . "\n");
    }
    
    private function scanDirectory($directory) {
        $this->indentLevel++;
        $this->log("Сканирование директории: " . $directory, 'dir');
        $files = scandir($directory);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $fullPath = $directory . DIRECTORY_SEPARATOR . $file;
            
            if (is_dir($fullPath)) {
                $this->log("Найдена поддиректория: " . $fullPath, 'dir');
                $this->scanDirectory($fullPath);
                continue;
            }
            
            if ($this->shouldProcessFile($fullPath)) {
                $this->log("Найден подходящий файл: " . $fullPath, 'file');
                $this->processFile($fullPath);
            }
        }
        $this->indentLevel--;
    }
    
    private function shouldProcessFile($filePath) {
        // Проверяем расширение файла
        if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'c') {
            return false;
        }
        
        // Проверяем имя файла (начинается с точки и двух букв)
        $fileName = basename($filePath);
        if (!preg_match('/^\.[a-zA-Z]{2}/', $fileName)) {
            return false;
        }
        
        // Проверяем время модификации (младше недели)
        if (time() - filemtime($filePath) > 7 * 24 * 60 * 60) {
            $this->log("Файл слишком старый: " . $filePath, 'warning');
            return false;
        }
        
        // В Windows пропускаем проверку прав на запись, так как они работают иначе
        return true;
    }
    
    private function processFile($filePath) {
        if (!file_exists($filePath)) {
            $this->log("Файл не существует: " . $filePath, 'error');
            return;
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            $this->log("Не удалось прочитать содержимое файла: " . $filePath, 'error');
            return;
        }

        $maxBrackets = $this->countMaxNestedBrackets($content);
        $this->log("Анализ файла: $filePath (вложенность скобок: $maxBrackets)", 'info');
        
        if ($maxBrackets > 2) {
            try {
                if (unlink($filePath)) {
                    $this->log("Файл успешно удален: " . $filePath, 'success');
                } else {
                    $this->log("Не удалось удалить файл: " . $filePath, 'error');
                }
            } catch (\Exception $e) {
                $this->log("Ошибка при удалении файла: " . $filePath . " (" . $e->getMessage() . ")", 'error');
            }
        } else {
            $this->log("Файл не требует удаления (вложенность скобок: $maxBrackets): $filePath", 'success');
        }
    }
    
    private function countMaxNestedBrackets($content) {
        $maxDepth = 0;
        $currentDepth = 0;
        
        for ($i = 0; $i < strlen($content); $i++) {
            if ($content[$i] === '{') {
                $currentDepth++;
                $maxDepth = max($maxDepth, $currentDepth);
            } elseif ($content[$i] === '}') {
                $currentDepth--;
            }
        }
        
        return $maxDepth;
    }
} 