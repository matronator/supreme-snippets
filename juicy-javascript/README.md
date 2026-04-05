# Juicy JavaScript

## XMLHttpRequest

```lang:js
var xhr = new XMLHttpRequest()
xhr.onreadystatechange = function() {
    if (this.readyState === this.DONE) {
        console.log(this.status) // do something; the request has completed
    }
}
xhr.open("HEAD", "http://example.com") // replace with URL of your choosing
xhr.send()
```

*Source: https://stackoverflow.com/a/41008144/13604898*

## Attach multiple event listeners to the same function

```lang:js
const events = ['mouseleave', 'blur', 'pointerleave']
const element = document.getElementById('myElement')

events.forEach(event => {
    element.addEventListener(event, fn)
})

function fn() {
    console.log('hello')
}
```
