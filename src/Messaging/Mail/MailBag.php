<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

use FreshMail\Api\Client\Messaging\Mail\Exception\ContentMismatchException;
use FreshMail\Api\Client\Messaging\Mail\Exception\ExternalFileException;

class MailBag implements \JsonSerializable
{
    /**
     * @var Recipient[]
     */
    private $tos;

    /**
     * @var From
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var Content[]
     */
    private $contents = [];

    /**
     * @var Header[]
     */
    private $headers = [];

    /**
     * @var Attachment[]
     */
    private $attachments = [];

    /**
     * @var string
     */
    private $templateHash;

    /**
     * @param string $email
     * @param array $customFields
     * @param array $customHeaders
     * @throws Exception\InvalidCustomFieldException
     * @throws Exception\InvalidHeaderException
     */
    public function addRecipientTo(string $email, array $customFields = [], array $customHeaders = []): void
    {
        $this->tos[] = new Recipient($email, $customFields, $customHeaders);
    }

    /**
     * @param string $fromEmail
     * @param string $fromName
     */
    public function setFrom(string $fromEmail, string $fromName): void
    {
        $this->from = new From(new Email($fromEmail), $fromName);
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @param string $body
     * @throws ContentMismatchException
     * @throws Exception\InvalidContentBodyException
     */
    public function setHtml(string $body): void
    {
        if ($this->templateHash) {
            throw new ContentMismatchException('You cannot set content while template hash is set');
        }

        $this->replaceContent(ContentType::HTML(), $body);
    }

    /**
     * @param string $body
     * @throws ContentMismatchException
     * @throws Exception\InvalidContentBodyException
     */
    public function setText(string $body): void
    {
        if ($this->templateHash) {
            throw new ContentMismatchException('You cannot set content while template hash is set');
        }

        $this->replaceContent(ContentType::TEXT(), $body);
    }

    /**
     * @param string $hash
     * @throws ContentMismatchException
     */
    public function setTemplateHash(string $hash): void
    {
        if ($this->contents) {
            throw new ContentMismatchException('You cannot set template hash while html or text content is set');
        }

        $this->templateHash = $hash;
    }

    /**
     * @param string $filepath
     * @throws ExternalFileException
     * @throws Exception\FileDoesNotExistException
     */
    public function addAttachment(string $filepath): void
    {
        $this->attachments[] = new Attachment($filepath);
    }

    /**
     * @param string $name
     * @param string $value
     * @throws Exception\InvalidHeaderException
     */
    public function addHeader(string $name, string $value): void
    {
        $this->headers[] = new Header($name, $value);
    }

    /**
     * @param array $headers
     * @throws Exception\InvalidHeaderException
     */
    public function addHeaders(array $headers): void
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->from->getEmail();
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->from->getName();
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        $content = $this->getContent(ContentType::HTML());
        if ($content !== null) {
            return $content->getBody();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        $content = $this->getContent(ContentType::TEXT());
        if ($content !== null) {
            return $content->getBody();
        }

        return '';
    }

    /**
     * @return Header[]
     */
    private function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Recipient[]
     */
    private function getRecipients(): array
    {
        return $this->tos;
    }

    /**
     * @return string
     */
    private function getTemplateHash(): ?string
    {
        return $this->templateHash;
    }

    /**
     * @param ContentType $contentType
     * @param string $body
     * @throws Exception\InvalidContentBodyException
     */
    private function replaceContent(ContentType $contentType, string $body): void
    {
        foreach ($this->contents as $id => $content) {
            if ($content->getContentType() == $contentType) {
                unset($this->contents[$id]);
            }
        }

        $this->contents[] = new Content($contentType, $body);
    }

    /**
     * @param ContentType $contentType
     * @return string
     */
    private function getContent(ContentType $contentType): Content
    {
        foreach ($this->contents as $content) {
            if ($content->getContentType() == $contentType) {
                return $content;
            }
        }

        return null;
    }
    /**
     * @return array
     */
    function jsonSerialize(): array
    {
        $recipients = [];
        foreach ($this->getRecipients() as $recipient) {
             $tmp = [
                'email' => $recipient->getEmail(),
            ];

            foreach ($recipient->getCustomFields() as $customField) {
                $tmp['personalization'][$customField->getPersonalizationTag()] = $customField->getValue();
            }

            foreach ($recipient->getHeaders() as $header) {
                $tmp['headers'][$header->getName()] = $header->getValue();
            }

            $recipients[] = $tmp;
        }

        $contents = [];
        foreach ($this->contents as $content) {
            $contents[] = [
                'type' => $content->getContentType()->getValue(),
                'body' => $content->getBody()
            ];
        }

        $templateHash = $this->getTemplateHash();

        $headers = [];
        foreach ($this->getHeaders() as $header) {
            $headers[$header->getName()] = $header->getValue();
        }

        $from = [];
        $from['email'] = $this->getFromEmail();
        if ($this->getFromName()) {
            $from['name'] = $this->getFromName();
        }

        $data = [
            'from' => $from,
            'subject' => $this->getSubject(),
            'recipients' => $recipients
        ];

        if ($contents) {
            $data['contents'] = $contents;
        }

        if ($templateHash) {
            $data['templateHash'] = $templateHash;
        }

        if ($headers) {
            $data['headers'] = $headers;
        }

        foreach ($this->getAttachments() as $attachment) {
            $data['attachments'][] = $attachment->toArray();
        }

        return $data;
    }
}