<?php

namespace ZedanLab\Paymob;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ZedanLab\Paymob\Services\PaymobOptions;

class Paymob
{
    /**
     * @var \ZedanLab\Paymob\Services\PaymobOptions
     */
    protected $options;

    /**
     * @var \Illuminate\Database\Eloquent\Model|\ZedanLab\Paymob\Contracts\ShouldGeneratesUsername
     */
    protected $model;

    /**
     * @var string
     */
    protected $username;

    /**
     * Generate username on create event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string                              $event
     * @return void
     */
    public function generateFor(Model $model, string $event)
    {
        $this->setOptions($model);

        if (! $this->options->getAttribute($event)) {
            return;
        }

        $this->addUsername();
    }

    /**
     * Adding username field to model.
     *
     * @return void
     */
    protected function addUsername()
    {
        $this->model->{$this->options->getAttribute('field')} = $this->build($this->model->{$this->options->getAttribute('source')});
    }

    /**
     * Filling the given model options if the LocalizedUsername method exists.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected function setOptions(Model $model)
    {
        $this->model = $model;
        $this->options = new PaymobOptions($model);
    }

    /**
     * @param  string   $type
     * @return string
     */
    protected function getWord(string $type = 'noun'): string
    {
        $type = Str::plural(strtolower($type));
        $max = count($this->options->getAttribute('dictionary')[$type]) - 1;

        return $this->options->getAttribute('dictionary')[$type][rand(0, $max)];
    }

    /**
     * Generate the username.
     *
     * @param  string|null $text
     * @return string
     */
    protected function build(?string $text = null): string
    {
        if ($text === null) {
            $text = $this->getWord('adjective') . ' ' . $this->getWord('noun');
        }

        $this->username = $text;

        $this->toAscii();
        $this->stripUnwantedCharacters();
        $this->convertToLowerCase();
        $this->collapseWhitespace();
        $this->addSeparator();
        $this->makeUnique();

        return $this->username;
    }

    /**
     * Convert the case of the username.
     *
     *
     * @return self
     */
    protected function convertToLowerCase(): self
    {
        try {
            $this->username = Str::lower($this->username);
        } catch (\BadMethodCallException$e) {
        }

        return $this;
    }

    /**
     * Remove unwanted characters.
     *
     * @return self
     */
    protected function stripUnwantedCharacters(): self
    {
        $this->username = preg_replace('/[^a-zA-Z\s]/u', '', $this->username);

        return $this;
    }

    /**
     * Trim spaces down.
     *
     * @return self
     */
    protected function collapseWhitespace(): self
    {
        $this->username = preg_replace('/\s+/', ' ', trim($this->username));

        return $this;
    }

    /**
     * Replaces spaces with a separator.
     *
     *
     * @return self
     */
    protected function addSeparator(): self
    {
        $this->username = preg_replace('/ /', $this->options->getAttribute('separator'), $this->username);

        return $this;
    }

    /**
     * Make the username unique.
     *
     * @return self
     */
    protected function makeUnique(): self
    {
        while (! $this->checkIfUnique()) {
            /**
             * @phpstan-ignore-next-line
             */
            $this->username .= ($this->model::whereUsernameLike($this->username)->count() + 1) . Str::random(3);
        }

        return $this;
    }

    /**
     * Check if the username is unique.
     *
     * @return bool
     */
    protected function checkIfUnique(): bool
    {
        if (! is_null($checkIfUnique = $this->options->getAttribute('unique'))) {
            if ($checkIfUnique instanceof Closure) {
                $isUnique = $checkIfUnique($this->username);
            }

            // check if unique via model trait
            elseif (method_exists($this->model, 'isUsernameUnique')) {
                $isUnique = $this->model::isUsernameUnique($this->username, $this->model);
            }

            if (! ($isUnique ?? true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert text to ascii code.
     *
     * @return self
     */
    protected function toAscii(): self
    {
        if ($this->options->getAttribute('convert_to_ascii')) {
            $this->username = Str::ascii($this->username);
        }

        return $this;
    }
}
