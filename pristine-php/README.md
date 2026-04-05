# Pristine PHP

![Pristine PHP logo](pristine-php.jpg)

Collection of useful PHP functions and code snippets.

<!-- TOC -->

- [Pristine PHP](#pristine-php)
    - [Advanced string Interpolation](#advanced-string-interpolation)
    - [Casting](#casting)
    - [Folder exists](#folder-exists)
    - [JSON to class](#json-to-class)
    - [One time file download](#one-time-file-download)
    - [String capitalization](#string-capitalization)

<!-- /TOC -->

## Advanced string Interpolation

> Source: https://stackoverflow.com/a/15410466/13604898

```php
function identity(mixed $arg): mixed {
    return $arg;
}
$interpolate = "identity";

echo "<input value='{$interpolate(1 + 1 * random_int())}' />";
```

## Casting

See [Casting.php](Casting.php)

## Folder exists

```php
/**
 * Checks if a folder exist and return canonicalized absolute pathname (sort version)
 * @param string $folder the path being checked.
 * @return mixed returns the canonicalized absolute pathname on success otherwise FALSE is returned
 */
function folder_exist(string $folder): string|false
{
    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    return ($path !== false AND is_dir($path)) ? $path : false;
}
```

## JSON to class

See [JSON2Class.php](JSON2Class.php)

## One time file download

See [OnetimeFileDownload.php](OnetimeFileDownload.php)

## String capitalization

> Source: https://stackoverflow.com/a/70378792

```php

// Source - https://stackoverflow.com/a/70378792
// Posted by dearsina, modified by community. See post 'Timeline' for change history
// Retrieved 2026-01-07, License - CC BY-SA 4.0

/**
 * Over-engineered solution to most capitalisation issues.
 *
 * @author https://stackoverflow.com/users/429071/dearsina
 * @version 1.0
 */
class str {
    /**
     * Words or abbreviations that should always be all uppercase
     */
    const ALL_UPPERCASE = [
        "UK",
        "VAT",
    ];

    /**
     * Words or abbreviations that should always be all lowercase
     */
    const ALL_LOWERCASE = [
        "and",
        "as",
        "by",
        "in",
        "of",
        "or",
        "to",
    ];

    /**
     * Honorifics that only contain consonants.
     *
     */
    const CONSONANT_ONLY_HONORIFICS = [
        # English
        "Mr",
        "Mrs",
        "Ms",
        "Dr",
        "Br",
        "Sr",
        "Fr",
        "Pr",
        "St",

        # Afrikaans
        "Mnr",
    ];

    /**
     * Surname prefixes that should be lowercase,
     * unless not following another word (firstname).
     */
    const SURNAME_PREFIXES = [
        "de la",
        "de las",
        "van de",
        "van der",
        "vit de",
        "von",
        "van",
        "del",
        "der",
    ];

    /**
     * Capitalises every (appropriate) word in a given string.
     *
     * @param string|null $string
     *
     * @return string|null
     */
    public static function capitalise(?string $string): ?string
    {
        if(!$string){
            return $string;
        }

        # Strip away multi-spaces
        $string = preg_replace("/\s{2,}/", " ", $string);

        # Ensure there is always a space after a comma
        $string = preg_replace("/,([^\s])/", ", $1", $string);

        # A word is anything separated by spaces or a dash
        $string = preg_replace_callback("/([^\s\-\.]+)/", function($matches){
            # Make the word lowercase
            $word = mb_strtolower($matches[1]);

            # If the word needs to be all lowercase
            if(in_array($word, self::ALL_LOWERCASE)){
                return strtolower($word);
            }

            # If the word needs to be all uppercase
            if(in_array(mb_strtoupper($word), self::ALL_UPPERCASE)){
                return strtoupper($word);
            }

            # Create a version without diacritics
            $transliterator = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', \Transliterator::FORWARD);
            $ascii_word = $transliterator->transliterate($word);


            # If the word contains non-alpha characters (numbers, &, etc), with exceptions (comma, '), assume it's an abbreviation
            if(preg_match("/[^a-z,']/i", $ascii_word)){
                return strtoupper($word);
            }

            # If the word doesn't contain any vowels, assume it's an abbreviation
            if(!preg_match("/[aeiouy]/i", $ascii_word)){
                # Unless the word is an honorific
                if(!in_array(ucfirst($word), self::CONSONANT_ONLY_HONORIFICS)){
                    return strtoupper($word);
                }
            }

            # If the word contains two of the same vowel and is 3 characters or fewer, assume it's an abbreviation
            if(strlen($word) <= 3 && preg_match("/([aeiouy])\1/", $word)){
                return strtoupper($word);
            }

            # Ensure O'Connor, L'Oreal, etc, are double capitalised, with exceptions (d')
            if(preg_match("/\b([a-z]')(\w+)\b/i", $word, $match)){
                # Some prefixes (like d') are not capitalised
                if(in_array($match[1], ["d'"])){
                    return $match[1] . ucfirst($match[2]);
                }

                # Otherwise, everything is capitalised
                return strtoupper($match[1]) . ucfirst($match[2]);
            }

            # Otherwise, return the word with the first letter (only) capitalised
            return ucfirst($word);
            //The most common outcome
        }, $string);

        # Cater for the Mc prefix
        $pattern = "/(Mc)([b-df-hj-np-tv-z])/";
        //Mc followed by a consonant
        $string = preg_replace_callback($pattern, function($matches){
            return "Mc" . ucfirst($matches[2]);
        }, $string);

        # Cater for Roman numerals (need to be in all caps)
        $pattern = "/\b((?<![MDCLXVI])(?=[MDCLXVI])M{0,3}(?:C[MD]|D?C{0,3})(?:X[CL]|L?X{0,3})(?:I[XV]|V?I{0,3}))\b/i";
        $string = preg_replace_callback($pattern, function($matches){
            return strtoupper($matches[1]);
        }, $string);

        # Cater for surname prefixes (must be after the Roman numerals)
        $pattern = "/\b (".implode("|", self::SURNAME_PREFIXES).") \b/i";
        //A surname prefix, bookended by words
        $string = preg_replace_callback($pattern, function($matches){
            return strtolower(" {$matches[1]} ");
        }, $string);

        # Cater for ordinal numbers
        $pattern = "/\b(\d+(?:st|nd|rd|th))\b/i";
        //A number suffixed with an ordinal
        $string = preg_replace_callback($pattern, function($matches){
            return strtolower($matches[1]);
        }, $string);

        # And we're done done
        return $string;
    }
}

```
