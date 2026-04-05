# Astonishing Apache

## Stop the server

```bash
sudo service apache2 stop
```

## Redirects

### Simple redirect

```apache
Redirect 301 / https://example.com/
```

### With regular expressions

```apache
RedirectMatch "^/oldfile(\.(html|php)|/)?$" "https://example.com/newfile.php"
```
