Contributing
============
We are open for any contributions - especially targeting content. This document describes
rules you should adhere when proposing changes.

Content structure
-----------------
All sheets are placed in [sheets/ direcory](http://github.com/overdocs/overdocs/tree/master/sheets). Each of them
is placed in directory, which indicates its category (directory names are mapped to their representative
categories in [config/categories.php](https://github.com/overdocs/overdocs/tree/master/config/categories.php)).

Filename is automatically used as sheet's ID (`overdocs.net/category/id`). Therefore you should
care about naming it properly. Filenames should be possibly short and written using gerunds (e.g.
`validating-url`).

Content markup
--------------
Sheet files are written in XML. The unit of indentation is 4 spaces, and UNIX-style newlines should be used.
Sheet files have `.xml` extension.

The basic sheet structure is defined as follows:

```xml
<?xml version="1.0"?>

<sheet xmlns="http://overdocs.net/overdocs">
    <title></title>
    <summary>Briefly describe the sheet contents.</summary>
    <cheat title="Section title" id="section-id" version="PHP 5.2+">
        [CONTENT]
    </cheat>

    <learn-more>
        <link href="https://tools.ietf.org/html/rfc2822">RFC 2822</link>
        <sheet-link category="php" id="sending-email">Sending email in PHP</sheet-link>
    </learn-more>

    <keywords>
        <keyword>some</keyword>
        <keyword>descriptive</keyword>
        <keyword>keywords</keyword>
    </keywords>
</sheet>
```

Root tag is always `<sheet>`. Namespace declaration is obligatory and it's the only allowed
attribute for this tag. Sheet must contain following tags:

- `<title>` - sheet title - keep it short and descriptive
- `<summary>` - a bit longer content summary, used e.g. in search results; should not exceed 80 chars
- at least one `<cheat>` tag
- `<keywords>` - each keyword placed in separate tag - be careful here, they are key of the searching engine

Moreover, sheet can contain one `<learn-more>` section. You can place a few helpful references there.

### `<cheat>` tag
Cheat tag represents basic unit of the sheet, so there must be at least one in your document. Cheats
cannot be nested.

__obligatory attributes__

- `title` - title of given cheat/section
- `id` - identifier (words separated by hyphens) - should be possibly short and unique within the sheet

__optional attributes__

-   `version` - if given solution is available only in some new versions, you should speficy it here.
    Don't add version number if it is very old or common (e.g. specifying `PHP 3+` makes no sense).

### Additional tags

#### `<snippet>`
This tag along with CDATA section is used to insert code block into the sheet. You have to specify
`language` attribute here. Currently possible values are: markup, css, clike, c, cpp, javascript and php.

CDATA block should not be indented. Example:
```xml
    <cheat [...]>
        [some content...]
        <snippet language="php">
<![CDATA[<?php
if (filter_var('foo@example.com', FILTER_VALIDATE_EMAIL)) {
    echo 'email is correct';
}]]>
        </snippet>
        [more content...]
    </cheat>
```

#### `<code>`
Used for __inline__ code - variable names, commands etc. Example
```xml
As defined in <code>$name</code> variable, you should...
```

#### `<image>`
Creates image tag with appropriate `<figure>` markup. Allowed attributes

- `src` (required): all paths are relative to `public/images/sheets/sheet-category/sheet-id`. If you need
  to reference another directory, use `../` and the path will be converted to canonical form.
- `title` - if specified, it creates `<figcaption>` and it's also used as a value of `alt`, if it's not
  exclusively specified by attribute `alt`.
- `alt` - alternative text for the image. If both `title` and `alt` are used, `title` is only used as a value
  of `<figcaption>`
- `height` of the image in pixels
- `width` of the image in pixels

#### `<warning>`
Creates warning block. Content should be placed inside paragraphs (`<p>`). Example
```xml
    <warning>
        <p>
            Don't do this at home!
        </p>
    </warning>
```

#### `<note>`
Creates note block. Content should be placed inside paragraphs (`<p>`).
```xml
    <note>
        <p>
            When doing this at home, please be careful!
        </p>
    </note>
```

#### `<link>`
Creates _external_ link. `href` is the only possible attribute. Example:
```xml
<link href="http://en.wikipedia.org">Wikipedia</a>
```

#### `<sheet-link>`
Creates a link to the another sheet. Obligatory attributes are `category` and `id`. Example code:
```xml
<sheet-link category="php" id="sending-email">Sending email in PHP</sheet-link>
```
will point at `http://overdocs.net/php/sending-email`

#### `<abbr>`
Tag for abbreviations takes no attributes. Values they stands for are kept in
`config/abbreviations.php`, so tag like this:
```xml
<abbr>SAPI</abbr>
```
combined with proper entry in PHP file:
```php
'SAPI' => 'Server Application Programming Interface',
```
will result in following HTML markup:
```html
<abbr title="Server Application Programming Interface">SAPI</abbr>
```

### Allowed HTML tags
Attributes are not allowed, if it's not meant exclusively.

- `p`
- `strong`, `em`
- `ul`, `ol`, `li`
- `table`, `table > caption`, `table > tr`
- `table > tr > td`, `table > tr > th` - you can set `colspan` for them

If you need more examples, just browse already existing sheets.

Validating markup rules
-----------------------
Before submitting new sheet, you are strongly requested to validate your changes. This will look for
most common mistakes and therefore save your and our time.

We have provided `lint.php` to simplify this task as much as it's possible. When executed without arguments,
it validates all sheets from the `sheets/` directory. You can also supply any glob expression if your shell
supports it.

You are obliged to follow rules listed below. Linter will report most of the violations automatically.

1. All files should be encoded with UTF-8 without BOM
2. For indentation use four spaces
3. Lines should not exceed 110 characters
4. Leave empty line at the end of the file
5. To separate words in identifiers use hyphens
6. Don't forget about the XML prolog
7. Only Unix line endings (LF) should be used

Submitting changes
------------------
Preferable way to submit changes or new content is to use GitHub Pull Requests. If you made too much
commits, squash them before submitting PR. Commit affecting content should look like the following:
```
[category/sheet-id] change description

Optional longer description, which should be word-wrapped to 72 chars,
due to the standard console window width.
```
Example:
```
[php/validating-email] mention about MX record validation
```
or
```
[html/urls] fix URL format

According to RFC 666 every URL should end with "ave satan"!
```

If your commit is related to more than one sheet, just prefix its description with `[content]`.
