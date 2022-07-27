<?php

declare(strict_types=1);

namespace Forge\User\Form;

use Forge\FormValidator\Error;
use Forge\FormValidator\FormValidator;
use Forge\User\ActiveRecord\Profile;
use Forge\User\Repository\ProfileRepository;
use Stringable;
use Throwable;
use Yiisoft\Db\Exception\InvalidArgumentException;
use Yiisoft\Db\Exception\StaleObjectException;
use Yiisoft\Strings\Inflector;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\InRange;
use Yiisoft\Validator\Rule\Url;

final class ProfileForm extends FormValidator
{
    use Error\EmailError;
    use Error\InRangeError;
    use Error\UrlError;

    private string $name = '';
    private string $publicEmail = '';
    private string $location = '';
    private string $website = '';
    private string $bio = '';
    private string $timezone = '';

    public function __construct(private ProfileRepository $profileRepository, private TranslatorInterface $translator)
    {
        parent::__construct();
    }

    public function getLabels(): array
    {
        return $this->profileTranslate();
    }

    public function getPlaceholders(): array
    {
        return $this->profileTranslate();
    }

    public function getRules(): array
    {
        return [
            'publicEmail' => [
                new Email(message: $this->getEmailErrorMessage(), skipOnEmpty: true),
            ],
            'website' => [
                new Url(message: $this->getUrlErrorMessage(), skipOnEmpty: true),
            ],
            'timezone' => [
                new InRange(range: timezone_identifiers_list(), message: $this->getInRangeErrorMessage()),
            ],
        ];
    }

    public function loadData(string $id): void
    {
        /** @var Profile $profile */
        $profile = $this->profileRepository->findById($id);
        $data = $profile->getAttributes();

        unset($data['identity_id']);

        $this->setAttributes($data);
    }

    public function setAttributes(array $data): void
    {
        /** @var array<string, object|scalar|Stringable|null> $data */
        foreach ($data as $name => $value) {
            $name = (new Inflector())->toCamelCase($name);

            if ($this->has($name)) {
                $this->setValue($name, $value);
            }
        }
    }

    /**
     * @throws InvalidArgumentException|StaleObjectException|Throwable
     */
    public function update(string $id): bool
    {
        /** @var Profile $profile */
        $profile = $this->profileRepository->findById($id);

        return (bool) $profile->updateAttributes(
            [
                'bio' => $this->bio,
                'location' => $this->location,
                'name' => $this->name,
                'public_email' => $this->publicEmail,
                'timezone' => $this->timezone,
                'website' => $this->website,
            ]
        );
    }

    /**
     * @psalm-return string[]
     */
    private function profileTranslate(): array
    {
        return [
            'bio' => $this->translator->translate('form.attribute.bio'),
            'location' => $this->translator->translate('form.attribute.location'),
            'name' => $this->translator->translate('form.attribute.name'),
            'publicEmail' => $this->translator->translate('form.attribute.public.email'),
            'timezone' => $this->translator->translate('form.attribute.timezone'),
            'website' => $this->translator->translate('form.attribute.website'),
        ];
    }
}
