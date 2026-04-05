# Lovely Linux

## LAMP

### Apache

#### Stop Apache

```bash
sudo service apache2 stop
```

#### Show connected addresses

```bash
netstat -an | grep :80 | sort
```
