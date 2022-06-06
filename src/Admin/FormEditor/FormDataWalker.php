<?php

namespace TLBM\Admin\FormEditor;

use Exception;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\Contracts\RecursiveFormDataWalkerInterface;
use TLBM\Admin\FormEditor\Elements\FormInputElem;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;
use Traversable;

class FormDataWalker
{
    /**
     * @var mixed
     */
    private $formDataTree;

    /**
     * @var FormElementsCollectionInterface
     */
    private FormElementsCollectionInterface $elementsCollection;

    /**
     * @param FormElementsCollectionInterface $elementsCollection
     */
    public function __construct(FormElementsCollectionInterface $elementsCollection)
    {
        $this->elementsCollection = $elementsCollection;
    }

    /**
     * @param mixed $formDataTree
     */
    public function setFormDataTree($formDataTree)
    {
        $this->formDataTree = $formDataTree;
    }

    /**
     * @return mixed
     */
    public function getFormDataTree()
    {
        return $this->formDataTree;
    }

    /**
     * @return Traversable
     */
    public function walk(): Traversable
    {
        yield from $this->walkRecursiveTraversable($this->formDataTree);
    }

    /**
     * @param array $inputVars
     * @param string $classFilter
     *
     * @return Traversable
     */
    public function walkLinkedElements(array $inputVars = array(), string $classFilter = FormInputElem::class): Traversable
    {
        foreach ($this->walk() as $formNode) {
            if (isset($formNode['formData']['uniqueName'])) {
                $formElem = $this->elementsCollection->getElemByUniqueName($formNode['formData']['uniqueName']);
                if ($formElem instanceof $classFilter) {
                    yield new LinkedFormData($formNode, $formElem, $inputVars);
                }
            }
        }
    }

    /**
     * @param mixed $subFormData
     *
     * @return Traversable
     */
    private function walkRecursiveTraversable($subFormData): Traversable
    {
        if(isset($subFormData['formData'])) {
            yield $subFormData;
        }

        if(isset($subFormData['children'])) {
            if(count($subFormData['children']) > 0) {
                foreach ($subFormData['children'] as $child) {
                    yield from $this->walkRecursiveTraversable($child);
                }
            }
        }
    }

    /**
     * @param RecursiveFormDataWalkerInterface $recursiveformWalker
     *
     * @return mixed
     */
    public function walkCallback(RecursiveFormDataWalkerInterface $recursiveformWalker)
    {
        return $this->walkCallbackRecursive($recursiveformWalker, $this->formDataTree);
    }

    /**
     * @param RecursiveFormDataWalkerInterface $recursiveformWalker
     * @param mixed $formNode
     *
     * @return mixed
     */
    private function walkCallbackRecursive(RecursiveFormDataWalkerInterface $recursiveformWalker, $formNode)
    {
        $children = $formNode['children'] ?? [];
        $formData = $formNode['formData'] ?? null;

        if ($formData && $formData['uniqueName']) {
            $registeredElement = $this->elementsCollection->getElemByUniqueName($formData['uniqueName']);
            if ($registeredElement) {
                if (count($children) == 0) {
                    return $recursiveformWalker->walk($formNode, $registeredElement, $children, function () {
                    });
                } else {
                    return $recursiveformWalker->walk($formNode, $registeredElement, $children, function ($childNode) use ($recursiveformWalker) {
                        return $this->walkCallbackRecursive($recursiveformWalker, $childNode);
                    });
                }
            }
        } else {
            return $recursiveformWalker->walk([], null, $children, function ($childNode) use ($recursiveformWalker) {
                return $this->walkCallbackRecursive($recursiveformWalker, $childNode);
            });
        }

        return null;
    }

    /**
     * @param mixed $formData
     *
     * @return ?FormDataWalker
     */
    public static function createFromData($formData): ?FormDataWalker
    {
        try {
            $formDataWalker = MainFactory::create(FormDataWalker::class);
            $formDataWalker->setFormDataTree($formData);

            return $formDataWalker;
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                $escaping = MainFactory::get(EscapingInterface::class);
                die($escaping->escHtml($exception->getMessage()));
            }
        }

        return null;
    }
}