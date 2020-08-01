<?php

/**
 * AnnotationReader.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

/**
 * Class AnnotationReader
 * @package JWorman\AnnotationReader
 */
class AnnotationReader
{
    const CLASS_NAME = __CLASS__;

    /**
     * ClassName => [PropertyName => Annotations[]]
     *
     * @var array<string, array<string, array<AbstractAnnotation>>>
     */
    private $propertyAnnotationsCache = array();

    /**
     * @param \ReflectionProperty $reflectionProperty
     * @return AbstractAnnotation[][]
     */
    public function getPropertyAnnotations(\ReflectionProperty $reflectionProperty)
    {
        $reflectionClass = $reflectionProperty->getDeclaringClass();
        $className = $reflectionClass->getName();
        if (isset($this->propertyAnnotationsCache[$className])) {
            return $this->propertyAnnotationsCache[$className];
        }

        $fileParser = new FileParser($reflectionClass);
        $propertyAnnotations = array();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $docComment = $reflectionProperty->getDocComment();
            if ($docComment === false) {
                $propertyAnnotations[$propertyName] = array();
                continue;
            }
            $annotationData = $fileParser->getAnnotationDataFromDocComment($docComment);
            $propertyAnnotations[$propertyName] = AnnotationFactory::batchCreate($annotationData);
        }

        $this->propertyAnnotationsCache[$className] = $propertyAnnotations;
        return $propertyAnnotations;
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     * @param string $annotationName
     * @return AbstractAnnotation
     * @throws PropertyAnnotationNotFound
     */
    public function getPropertyAnnotation(\ReflectionProperty $reflectionProperty, $annotationName)
    {
        $annotations = $this->getPropertyAnnotations($reflectionProperty);
        foreach ($annotations[$reflectionProperty->getName()] as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
            }
        }
        throw new PropertyAnnotationNotFound($reflectionProperty, $annotationName);
    }
}
