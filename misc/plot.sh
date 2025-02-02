#!/usr/bin/gnuplot
reset
set encoding utf8

set terminal svg size 768, 512 font ',18' lw 2
set output 'plot.svg'

set size ratio 0.51803

set ylabel "Время, мс"
set xlabel "Количество строк"

set log y 5

set key left top;

set grid

set title "Время выполнения функции count accepted contracts"

plot "./data.txt" using 1:3 with lp pt 8 dt 3 title "Без индекса", \
        "./data.txt" using 1:2 with lp pt 6 dt 2 title "С индексом"
