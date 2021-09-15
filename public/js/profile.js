let plus = document.getElementsByClassName("plus-button")
let moins = document.getElementsByClassName("minus-button")
let div;
let number;
for (let i = 0; i < plus.length; i = i + 1) {
    plus[i].addEventListener("click", () => {
        div = plus[i].nextElementSibling

        number = parseInt(div.value)
        number += 1


        if (number < 10) {
            number = number.toString()
            div.value = '0' + number;
        } else  {
            number = number.toString()
            div.value = number;
        }


    });
    moins[i].addEventListener("click", () => {
        div = plus[i].nextElementSibling
        number = parseInt(div.value)
        if (number > 0) {
            number -= 1
            if (number < 10) {
                number = number.toString()
                div.value = '0' + number;
            } else  {
                number = number.toString()
                div.value = number;
            }

        }

    });


}