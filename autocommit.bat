set INTERVAL=20
:loop


git add --all
git commit -a -m "Rifat: Commit %date% %time% %random%"
git push

timeout %INTERVAL%
goto:loop
