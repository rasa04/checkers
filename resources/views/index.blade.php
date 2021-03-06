<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Checkers</title>
</head>
<body>
    <div id="desk">
        @for ($j = 1; $j < 9; $j++)
            <div class="rows">
                @for ($i = 1; $i < 9; $i++)
                   @php
                       (($j+$i)%2 == 0) ? $color = "black" : $color = "white";
                   @endphp
                    <div 
                    onmouseover="mover({{ $j }},{{ $i }})" 
                    onmouseout="mout({{ $j }}, {{ $i }})" 
                    class="cage {{ $color }}" id="place-{{ $j . "-" .$i }}"
                    onclick="itemSelect({{ $j }},{{ $i }})"
                    ondblclick="moveItem({{ $j }}, {{ $i }})">
                        @if (($j < 4 || $j > 5) && $color == "black")
                            <div class="item @if ($j < 4) enemy @else mine @endif" id="item-{{ $j . "-" .$i }}">
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        @endfor
    </div>
    <div id="desk_score">
        <div id="score_enemy">0</div>
        <div id="score_mine">0</div>
    </div>
    <div id="menu">
        <div id="play_bot" onclick="step()">Play with bot</div>
    </div>
    <script>
        var j, i;
        function itemSelect(j, i) {
            if (document.getElementById("item-" + j + "-" + i) != null) {
                console.log("it works!", j, i);
                for (let r = 1; r < 9; r++) {
                    for (let c = 1; c < 9; c++) {
                        if(document.getElementById("place-" + r + "-" + c).style.background === "rgb(22, 94, 78)"){
                            document.getElementById("place-" + r + "-" + c).style.background = "black"
                        }
                    }
                }
                document.getElementById("place-"+ j + "-" + i).style.background = "rgb(22, 94, 78)"
            }

        }

        function mover(j, i) {
            if ((j+i)%2===0 && document.getElementById("place-"+j+"-"+i).style.background !== "rgb(22, 94, 78)") {
            document.getElementById("place-"+j+"-"+i).style.background = "rgb(163, 30, 30)"
            }
        }
        function mout(j, i) {
            if ((j+i)%2===0 && document.getElementById("place-"+j+"-"+i).style.background !== "rgb(22, 94, 78)") {
                document.getElementById("place-"+j+"-"+i).style.background = "black"
            }
        }

        function moveItem(j, i) {
            if ((j+i) % 2 === 0) { //???????????????? ?????????? ???????????? ?? ???????????? ??????????
                for (let r = 1; r < 9; r++) { //???????????????????? ????????
                    for (let c = 1; c < 9; c++) { //???????????????????? ??????????????
                        if(document.getElementById("place-" + r + "-" + c).style.background === "rgb(22, 94, 78)"
                            && document.getElementById("item-" + j + "-" + i) == null
                            && (
                                ((document.getElementById("item-" + r + "-" + c).classList[1] == 'mine') &&
                                (r - 1 === j) && (c + 1 === i || c - 1 === i)) ||
                                ((document.getElementById("item-" + r + "-" + c).classList[1] == 'enemy') &&
                                (r + 1 === j) && (c + 1 === i || c - 1 === i))
                            )
                        )
                        {
                            document.getElementById("place-" + j + "-" + i).innerHTML = 
                                "<div class=\"item\" id=\"item-" + j + "-" + i + "\"></div>"
                                
                            if (document.getElementById("item-" + r + "-" + c).classList[1] == 'mine') {
                                document.getElementById("item-" + j + "-" + i).classList.add('mine')
                            }else {
                                document.getElementById("item-" + j + "-" + i).classList.add('enemy')
                            }

                            if (document.getElementById("place-" + r + "-" + c).style.pointerEvents == 'none') {
                                document.getElementById("place-" + r + "-" + c).style.pointerEvents = 'auto'
                            }
                            document.getElementById("place-" + r + "-" + c).style.background = "black"
                            document.getElementById("item-" + r + "-" + c).remove()
                            queue()
                        }
                        else if(document.getElementById("place-" + r + "-" + c).style.background === "rgb(22, 94, 78)"
                            && document.getElementById("item-" + j + "-" + i) == null
                            && (
                                ((document.getElementById("item-" + r + "-" + c).classList[1] == 'mine') &&
                                    (r - 2 === j || r + 2 === j) && (c + 2 === i || c - 2 === i) &&
                                    (
                                        document.getElementById("item-" + ((j+r)/2) + "-" + ((i+c)/2)) != null &&
                                        document.getElementById("item-" + ((j+r)/2) + "-" + ((i+c)/2)).classList[1] == 'enemy'
                                    )
                                )
                                ||
                                ((document.getElementById("item-" + r + "-" + c).classList[1] == 'enemy') &&
                                (r + 2 === j || r - 2 === j) && (c + 2 === i || c - 2 === i) &&
                                    (
                                        document.getElementById("item-" + ((j+r)/2) + "-" + ((i+c)/2)) != null &&
                                        document.getElementById("item-" + ((j+r)/2) + "-" + ((i+c)/2)).classList[1] == 'mine'
                                    )
                                )
                            )
                        )
                        {
                            document.getElementById("place-" + j + "-" + i).innerHTML = 
                                "<div class=\"item\" id=\"item-" + j + "-" + i + "\"></div>"
                                
                            if (document.getElementById("item-" + r + "-" + c).classList[1] == 'mine') {
                                document.getElementById("item-" + j + "-" + i).classList.add('mine')
                            }else {
                                document.getElementById("item-" + j + "-" + i).classList.add('enemy')
                            }
                            document.getElementById("place-" + r + "-" + c).style.background = "black"

                            if (document.getElementById("place-" + r + "-" + c).style.pointerEvents == 'none') {
                                document.getElementById("place-" + r + "-" + c).style.pointerEvents = 'auto'
                            }
                            if (document.getElementById("place-" + ((j+r)/2) + "-" + ((i+c)/2)).style.pointerEvents == 'none') {
                                document.getElementById("place-" + ((j+r)/2) + "-" + ((i+c)/2)).style.pointerEvents = 'auto'
                            }

                            document.getElementById("item-" + r + "-" + c).remove()
                            document.getElementById("item-" + ((j+r)/2) + "-" + ((i+c)/2)).remove()

                            document.getElementById('score_mine').innerHTML = (12 - document.querySelectorAll('.enemy').length);
                            document.getElementById('score_enemy').innerHTML = (12 - document.querySelectorAll('.mine').length);
                            queue()
                        }
                    }
                }
            }
        }

        var q = "mine"
        function queue() {
            console.log("disabling");
            for (let el of document.querySelectorAll("."+q)) {
                el.parentElement.style.pointerEvents = 'none'
            }
            (q == 'mine') ? q = 'enemy' : q = 'mine'
            for (let el of document.querySelectorAll("."+q)) {
                el.parentElement.style.pointerEvents = 'auto'
            }
        }

        function step() {
            //
        }
    </script>
</body>
</html>