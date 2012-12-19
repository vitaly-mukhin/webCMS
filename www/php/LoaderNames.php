<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Виталик
 * Date: 19.12.12
 * Time: 16:41
 * To change this template use File | Settings | File Templates.
 */
namespace App;
class LoaderNames extends \VM\LoaderNames {

    /**
     * @param string $class
     *
     * @return string
     */
    public function getFile($class) {
        $class_array = $this->explodeClass($class);

        if ($class_array[0] == __NAMESPACE__) {
            array_shift($class_array);
        }

        if ($this->ignoreFirstPart && count($class_array) > 1) {
            array_shift($class_array);
        }

        if (count($class_array) > 1) {
            $path     = implode(DIRECTORY_SEPARATOR, array_slice($class_array, 0, -1));
            $filename = $path . DIRECTORY_SEPARATOR . $this->getFilePrefix($class_array[count($class_array) - 2]) . $class_array[count($class_array) - 1] . $this->getFileSuffix();
        } else {
            $filename = $this->getPrefix() . implode($class_array) . $this->getFileSuffix();
        }

        return $this->baseFolder . DIRECTORY_SEPARATOR . $filename;
    }
}
