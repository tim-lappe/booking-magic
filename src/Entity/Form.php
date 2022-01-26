<?php


namespace TLBM\Entity;

use JsonSerializable;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="forms")
 */
class Form implements JsonSerializable
{

    use IndexedTable;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false, unique=true)
     */
    protected string $title;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $timestamp_created;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="text", nullable=false)
     */
    protected string $frontend_html = "";

    /**
     * @Doctrine\ORM\Mapping\Column (type="json", nullable=false)
     */
    protected $form_data;

    public function __construct()
    {
        $this->timestamp_created = time();
    }

    public function jsonSerialize(): array
    {
        return array(
            "title"         => $this->GetTitle(),
            "form_data"     => $this->GetFormData(),
            "frontend_html" => $this->GetFrontendHtml()
        );
    }

    /**
     * @return string
     */
    public function GetTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function SetTitle(string $title): void
    {
        $this->title = $title;
    }

    public function GetFormData()
    {
        return $this->form_data;
    }

    /**
     * @param $form_data
     */
    public function SetFormData($form_data): void
    {
        $this->form_data = $form_data;
    }

    /**
     * @return string
     */
    public function GetFrontendHtml(): string
    {
        return $this->frontend_html;
    }

    /**
     * @param string $frontend_html
     */
    public function SetFrontendHtml(string $frontend_html): void
    {
        $this->frontend_html = $frontend_html;
    }
}