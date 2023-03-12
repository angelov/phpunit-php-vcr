# PHP-VCR integration for PHPUnit

A library that allows you to easily use the PHP-VCR library in your PHPUnit tests.

## Requirements

* PHP 8.1+
* PHPUnit 10+

## Installation

```
composer require --dev angelov/phpunit-php-vcr
```

Then, add the extension to your PHPUnit configuration file.

(All parameters are optional.)

```xml
    <extensions>
        <bootstrap class="\Angelov\PHPUnitPHPVcr\Extension">
            <parameter name="cassettesPath" value="tests/fixtures" />
            <parameter name="storage" value="yaml" />                                   <!-- https://php-vcr.github.io/documentation/configuration/#storage -->
            <parameter name="libraryHooks" value="stream_wrapper, curl, soap" />        <!-- https://php-vcr.github.io/documentation/configuration/#library-hooks -->
            <parameter name="requestMatchers" value="method, url, query_string, ..." /> <!-- https://php-vcr.github.io/documentation/configuration/#request-matching -->
            <parameter name="whitelistedPaths" value="" />                              <!-- https://php-vcr.github.io/documentation/configuration/#white--and-blacklisting-paths -->
            <parameter name="blacklistedPaths" value="" />                              <!-- https://php-vcr.github.io/documentation/configuration/#white--and-blacklisting-paths -->
            <parameter name="mode" value="new_episodes" />                              <!-- https://php-vcr.github.io/documentation/configuration/#record-modes -->
        </bootstrap>
    </extensions>
```

## Usage

The library provides an `UseCassette` attribute that can be declared on test classes or specific test methods. The 
attribute expects one string argument - the name of the cassette.

When running the tests, the library will automatically turn the recorder on and off, and insert the cassettes when 
needed.

**Examples:**

* When declared on a class, PHP-VCR will intercept the requests in all test methods in that class, and will store the 
responses in the given cassette.

    ```php
    use Angelov\PHPUnitPHPVcr\UseCassette;
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\TestCase;

    #[UseCassette("example_cassette.yml")]
    class ExampleTest extends TestCase
    {
        #[Test]
        public function example(): void { ... }
        
        #[Test]
        public function another(): void { ... }
    }
    ```

* When declared on a test method, only requests in that methods will be intercepted and stored in the given cassette. 
Note that it can applied on multiple test methods with different cassettes.

    ```php
    use Angelov\PHPUnitPHPVcr\UseCassette;
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\TestCase;

    class ExampleTest extends TestCase
    {
        #[Test]
        #[UseCassette("example.yml")]
        public function example(): void { ... }

        #[Test]
        public function another(): void { ... }

        #[Test]
        #[UseCassette("example_2.yml")]
        public function recorded(): void { ... }
    }
    ```
