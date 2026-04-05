# Glamorous Git

## Stop tracking file (delete tracked file from cache)

```bash
git rm --cached <file>
# OR
git rm -r --cached <folder>
```

## Remove remote

```bash
git remote remove origin
```

## Ignore local changes to tracked file

```bash
# Ignore
git update-index --assume-unchanged <file-to-ignore>

# Un-ignore
git update-index --no-assume-unchanged <file-to-ignore>
```
