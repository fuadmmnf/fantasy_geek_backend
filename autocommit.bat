set INTERVAL=5
:loop
git pull
git add --all
git commit -a -m "Commit %date% %time% %random%"
git push

timeout %INTERVAL%
goto:loop
