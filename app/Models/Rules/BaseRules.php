<?php

namespace App\Models\Rules;

abstract class BaseRules
{
    protected array $rules = [];
    protected array $messages = [];
    protected string $prefix = '';

    // Store an instance for chaining
    protected static ?self $instance = null;

    /**
     * Initialize the rules and messages defined in the child class.
     */
    public function __construct()
    {
        $this->rules = $this->defineRules();
        $this->messages = $this->defineMessages();
    }

    /**
     * Define validation rules in the child class.
     *
     * @return array An array of validation rules.
     */
    abstract protected function defineRules(): array;

    /**
     * Define custom validation messages in the child class.
     *
     * @return array An array of validation messages.
     */
    abstract protected function defineMessages(): array;

    /**
     * Get a singleton instance of the class for method chaining.
     *
     * @return static An instance of the class.
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Set a prefix for all validation fields.
     *
     * This prefix will be prepended to the field names in the rules and messages.
     *
     * @param string $prefix The prefix to apply.
     * @return static The current instance for chaining.
     */
    public static function prefix(string $prefix): self
    {
        $instance = self::instance();
        $instance->prefix = $prefix;
        return $instance;
    }

    /**
     * Add the 'required' rule to all fields except specified ones.
     *
     * @param string[] ...$except Fields to exclude from the 'required' rule.
     * @return static The current instance for chaining.
     */
    public static function required(...$except): self
    {
        $instance = self::instance();
        $except = self::mergeArgumentsIntoArray($except);
        foreach ($instance->rules as $field => &$rule) {
            if ($except && in_array($field, $except)) {
                continue;
            }
            if (!is_array($rule)) {
                $rule = explode('|', $rule);
            }
            if (!in_array('required', $rule)) {
                $rule[] = 'required';
            }
        }
        return $instance;
    }

    /**
     * Add the 'nullable' rule to all fields except specified ones.
     *
     * @param string[] ...$except Fields to exclude from the 'nullable' rule.
     * @return static The current instance for chaining.
     */
    public static function nullable(...$except): self
    {
        $instance = self::instance();
        $except = self::mergeArgumentsIntoArray($except);
        foreach ($instance->rules as $field => &$rule) {
            if (!is_array($rule)) {
                $rule = explode('|', $rule);
            }
            if ($except && in_array($field, $except)) {
                continue;
            }
            if (!in_array('nullable', $rule)) {
                $rule[] = 'nullable';
            }
        }
        return $instance;
    }

    /**
     * Append validation rules to a specific field.
     *
     * @param string $field The field to append rules to.
     * @param string[] ...$rules The rules to append.
     * @return static The current instance for chaining.
     */
    public static function append(string $field, ...$rules): self
    {
        $instance = self::instance();
        $rules = self::mergeArgumentsIntoArray($rules);
        if (isset($instance->rules[$field])) {
            $instance->rules[$field] = array_merge($rules, $instance->rules[$field]);
        } else {
            $instance->rules[$field] = $rules;
        }
        return $instance;
    }

    /**
     * Set specific rules for a field, replacing any existing rules.
     *
     * @param string $field The field to set rules for.
     * @param string[] ...$rules The rules to set.
     * @return static The current instance for chaining.
     */
    public static function set(string $field, ...$rules): self
    {
        $instance = self::instance();
        $rules = self::mergeArgumentsIntoArray($rules);
        $instance->rules[$field] = $rules;
        return $instance;
    }

    /**
     * Get the final validation rules array with prefixes applied.
     *
     * @return array The validation rules.
     */
    public static function getRules(): array
    {
        $instance = self::instance();
        $rules = $instance->getFinalRules();
        self::$instance = null; // Reset instance for the next call.
        return $rules;
    }

    /**
     * Get the final validation messages array with prefixes applied.
     *
     * @return array The validation messages.
     */
    public static function getMessages(): array
    {
        $instance = self::instance();
        $messages = $instance->getFinalMessages();
        self::$instance = null; // Reset instance for the next call.
        return $messages;
    }

    /**
     * Apply the prefix to all fields and return the final rules.
     *
     * @return array The prefixed rules.
     */
    private function getFinalRules(): array
    {
        if ($this->prefix) {
            $prefixedRules = [];
            foreach ($this->rules as $field => $rule) {
                $prefixedRules[$this->prefix . $field] = $rule;
            }
            return $prefixedRules;
        }
        return $this->rules;
    }

    /**
     * Apply the prefix to all fields and return the final messages.
     *
     * @return array The prefixed messages.
     */
    private function getFinalMessages(): array
    {
        if ($this->prefix) {
            $prefixedMessages = [];
            foreach ($this->messages as $field => $message) {
                $prefixedMessages[str_replace('*', $this->prefix, $field)] = $message;
            }
            return $prefixedMessages;
        }
        return $this->messages;
    }

    /**
     * Helper method to merge arguments into a single array.
     *
     * @param array $args The arguments to merge.
     * @return array The merged array.
     */
    private static function mergeArgumentsIntoArray(array $args): array
    {
        $finalArgs = [];
        foreach ($args as $arg) {
            $finalArgs = array_merge(is_array($arg) ? $arg : [$arg], $finalArgs);
        }
        return $finalArgs;
    }
}
