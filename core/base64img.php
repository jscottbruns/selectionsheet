<?php 
#
# base64img.php:  download latest version from: http://php.holtsmark.no
$ver="2.3";
# 
#  This script shows how you create compact and good scripts 
#  with everyting (image,download and phpcode) in one single file.
#  Enjoy !
#
#
# Rolf Holtsmark, php@holtsmark.no
# Terje Monsen, terje@holtsmark.no
#

# disable upload function by uncomment the next line:
#$f="testimg.gif"; // A JPG or GIF file... 


# $LineLength sets the lengt of the lines. Purely cosmetical.
# The default value is 30, Must be more than 0.
$LineLength=50;
# 

# check that /tmp/ is writable for the user apache runs as.
# 
#  NO EDITING NEEDED... Go to http://yourwebserver/base64img.php !
#
########################################################################

# THIS IS THE PHP.HOLTSMARK.NO IMAGE:
function holtsmarklogo() 
{
  header("Content-type: image/jpg");
  header("Content-length: 2630");
  echo base64_decode(
'/9j/4AAQSkZJRgABAgAAZABkAAD/7A'.
'ARRHVja3kAAQAEAAAAPAAA/+4AJkFk'.
'b2JlAGTAAAAAAQMAFQQDBgoNAAADPw'.
'AABP0AAAcoAAAKRP/bAIQABgQEBAUE'.
'BgUFBgkGBQYJCwgGBggLDAoKCwoKDB'.
'AMDAwMDAwQDA4PEA8ODBMTFBQTExwb'.
'GxscHx8fHx8fHx8fHwEHBwcNDA0YEB'.
'AYGhURFRofHx8fHx8fHx8fHx8fHx8f'.
'Hx8fHx8fHx8fHx8fHx8fHx8fHx8fHx'.
'8fHx8fHx8fHx8f/8IAEQgAFwClAwER'.
'AAIRAQMRAf/EALUAAAIDAQEBAAAAAA'.
'AAAAAAAAMFAAIEBgEHAQEBAQEBAAAA'.
'AAAAAAAAAAAAAgEDBBAAAQQDAAICAw'.
'AAAAAAAAAAAAECAwQREgUQEyFBIEAU'.
'EQACAQMCBQQCAwEAAAAAAAABAhEAEg'.
'MhMUFRYSIyEIGRE3EzQiMEFBIAAgMA'.
'AAAAAAAAAAAAAAAAEFBAEWETAQACAg'.
'IBAwMFAQEAAAAAAAEAESExQVFhEHGB'.
'8JGxQKHB4fHRIP/aAAwDAQACEQMRAA'.
'AB+qCDvzxXIxtytnzpH256s0JNbY3D'.
'c6ZqAtwFZ0fn6o+/M06y50TNhyPfng'.
'ncPSHvHoz3OW6SYNm6J3Jcq6wmCtY8'.
'6xXPV8r5jtBJ3oOVup2AioMKegiHpY'.
'oVIQuQuUPAwQ//2gAIAQEAAQUCL121'.
'DZXtrhOzaWpSvumknVyQ8/rMnY3rVX'.
'OWzYj6U3VSK5Pfhic7t02o3p1nQR9S'.
's+BvTisR8/rx2EE7FXabpV45I5GSM8'.
'dii+eaSlK4/gnWDnVljGtspXSjPG5t'.
'ayrJq9t1i1XndLcqTSo6lYVn8k6tr1'.
'rEaVobTYK9KatMz3pPPSs49Vtq85nq'.
'i8PRhiPGkAxIzDBWwmsIrYTWPGsONK'.
'5pAaQCNiNYTDBWxGsIxGeP/9oACAEC'.
'AAEFAhqJjQ0TLkEHMwaH0jPhGnrU0N'.
'DXA5mPGgjfwa/Ajjf5VcmUNzc2TCKN'.
'fg9h7BXir8q/J9bmyCrn9f8A/9oACA'.
'EDAAEFAhVNjYRfCKbH3kybGTJkR3jY'.
'z+CoYNRE8amDBgVDU1NTAjfGpj9j/9'.
'oACAECAgY/AjUnUf8A/9oACAEDAgY/'.
'An//2gAIAQEBBj8CrHixrjtyAkM7Ee'.
'O/DrX+d7UTHlYrkLNtbvB47aU2cJiN'.
'uSzyMETbO3OsmHKqjJjgyhuUhvjlTl'.
'Da0aHesS5JXK6zcVhSRvFKIcI5hMpU'.
'2E/mlxNkDYciswW2CIjjx3r6Didlsu'.
'lVLaz04Uqw7u4uCIsmOdKSuTuW7wOw'.
'MH4psoDwhtZbTd8U+ZQ/9Wjpb3fFN/'.
'z3AlCyZWQ21jxtJzFQXIHaCRO/D0i3'.
'IIax2KEBSefzTJDuU/YUUsF/NB0Nys'.
'JB9ceQsLE2xlZ331npWJlIVcX8Lfan'.
'xHKIdrj29Z586gADnAismN81+Rpte3'.
'YHYRWEfbK4Rb47g6c+VJhbLP8AnSIW'.
'3WF2BPtQzfeBEhRZwPvS58OSxrbDIn'.
'SZ6UhLq1ix3pOvPhFBft0CHHqJ8vem'.
'l5lg8Fe2QI1HGsypkC/bySAPYUcJyz'.
'C2obdoEc9aT6nhBAyCPLr0oucs4SsD'.
'FGx5zWVfu/bkGTw5e/QU5xZrBk7nlZ'.
'7oiR8UuIeKCB691bitxXb6bitxW4rc'.
'VuK3FbitxWhFbj01IrcVp6f/2gAIAQ'.
'EDAT8hi5tOra0aXSpZ6p9m2wFB2dzg'.
'OQ4RXyD7QaAM+wVlEJwYFEWuUGt1i4'.
'e+awcu96hEBCpzQHm6dQwXSyMBk/Yi'.
'8UF/Wpjwc9xVsiCrQo4q4JaN1Lg/lO'.
'NcJC0nspu4jgItE3XG7KbslsM4IQyZ'.
'q0uEM/8A+YhiLi0xmiKAG6tEwo5kwL'.
'yPGaIf8QfI5PVGMU6Ptg6cRx1lMw2W'.
'NlYep7eg/pqBpSt6q91LT4vg6gS695'.
'kCqmNC3KuEtD3HDUbnRTRLBg5XTil7'.
'63UNQGi+QZyGavgQvfJpsfEvU50qyc'.
'/CiBQt6yt82DuuIWQJoRppAyQwhDH0'.
'oWWgopgOKN7ZJMTx1+3PPG06MvBpj6'.
'2JnSNPAFlKumG5lbUu3j1TsHvPGPcn'.
'+kQWw+0btktZs9yf7hOV9wjhYPcjuU'.
'e5P9Mh/bkLcffId/II2cnuTzkb/IJ/'.
'uEAZj7en/9oACAECAwE/IYqzeJzOSd'.
'hqCFmmbTMrUXKM+ZZayIz2Iq6ihruP'.
'NNzj0v4ivUSvWgnL5lI9srlWjuXo2a'.
'ic43KbqDAr94dUlwP9QHXNwHUFrGvM'.
'NtU5hLOGYGzGitzsNS5f6f8A/9oACA'.
'EDAwE/IYlzhLVfoMvlJbDnUaSsrK1M'.
'9S70pH/xtY/slqqUSmoaeJfXEVd3FX'.
'ZLZ7/QUgqqUSsy3ctxAr9P/9oADAMB'.
'AAIRAxEAABCSpL2g/Z0CGSZm4jXMK2'.
'CSASCAACQSQCf/2gAIAQEDAT8Qiuc4'.
'GhNL1bvN1DUpxEnVdvRaBrNUNkI1A5'.
'IInz3ia4XAiK2JYeSVyyX2kV/guXhZ'.
'iAAwnK+q0sswRWxKPEgLhhpA8NWN5b'.
'hL4jTHAAIjb2IO1ZXUKqiJlgnmLZeU'.
'9hYvoJ3HWxJqpSRRHZRW4Ckoq7ByqG'.
'wlW8QgaAuubKUgSwWwWIeIe5B12gec'.
'QCWahKRYwGQUD5LwkFJVYIbWq+4a4m'.
'gQZg7C+x9RS31Cq0siAcIIfxAjAQc4'.
'Mq3DI8k3VhddA71iAlcg+Tpv8sPc3J'.
'sdDN/DqPIVF08XueTuKbwlOVKWZBWo'.
'b0oxaRqtjBsjTunRovgMomutwD8CHS'.
'WKa9YPbYUCdAKuP+nh1fLUYDkOZfow'.
'rsD0sKBjtV3hUj5vDhFck41ti+U4iU'.
'pb3cTkoLKbUMrMV58ErjO1Lyw2ty42'.
'7xSoGFZY7MGgTb4RmgbfLv1pflgfmL'.
'1fJwT6E/mAvlrD+JZrdyWRbT8r/wCk'.
'bivrPMYFfq/+kSV0XB+Y6nlmj8wq4P'.
'r5gTH0fmE2Hj/vEip8f9Iq792fmYz4'.
'8kUwnyH8xqr6z3lKI8h/Hp//2gAIAQ'.
'IDAT8QihMhoOYG7lASjd/WYkU5W0dX'.
'W/pghHJspxKUvJHSoh7tOrgR1ZsvP2'.
'l7BQQ3u7mjmayhDVsA5WDKDg1vmBOG'.
'fOIVVdG8RUY7yXmOkVk1nO69Ezelmd'.
'+0EGxerauMkdnqRg9hT24hBReW/rmB'.
'gvR9lddRrFfluOKUCrL3GXMne9V8dx'.
'tTRXm8Z21/cNa818j4g3WXe6gCUlvF'.
'Mdc3BK7o26+IBKRQm85bw1j+Yylfk+'.
'alO0zbnf7YhlyN4b141kjWOTcCnM+i'.
'vLBgLuGax0xVW39P/9oACAEDAwE/EI'.
'ABWYvLAMNEbqMqOzqWpqUi9pBO674l'.
'FLwkpxOuoarNxDv7QYvMGTnEAPZuoF'.
'DmvQ832gDWWCJZ61yuDipeiYOFRZW2'.
'3r+4AohcFzENvshUJwi/4H9xliuI6M'.
'nyRSV0rXcXnPTqMEHfiEyftErT7+YL'.
'K8dRQTk3r+5UtqvxDAHH6f8A/9k='.
'');
}

function base64imglogo() 
{
  header("Content-type: image/gif");
  header("Content-length: 5192");
  echo base64_decode(
'R0lGODlh3QAoANUAAHJyhtPT02RkZNra2np6emtrbEdHSPX19S'.
'UlJXNzc5ubm6KiorOzs8LCwsvLyysrZbu7u5CQkaqqqjo6dDEx'.
'a1VVeUpKZY2NjVRUbOnp6VtbXO7u7mRkgOXl5YWFhYmJifHx8f'.
'r6+kNDXYCAgEhIdlFRUSEhWuDg4Dk5VJWVlRoaUfj4+FZWgI+P'.
'pjs7PDk5ZD09al9fcC4uaIKCjSwsSDIyX25ufDU1bycnYYiIm1'.
'BQYD4+d4uLi/39/f7+/v///yH5BAAAAAAALAAAAADdACgAAAb/'.
'wJ9wSCz6jshkD7n0LXvQqHQqRf58V2xxy+16v8ak+Ph0jpnos5'.
'm8FjfN73P8eAXbv4pLg6iUhv6AgX8rISsrAQyGiouMgiFSHQxj'.
'dXRnDCFYEAdZk1g+C2pUPY6kpaaPp3+jqailolZad2ApHnpCTK'.
'uFhge8vbwMDiAgG8MbARIbycrLzMK9iiEgEqqvoj0IIFDY1lIE'.
'EaeKvuIg4gfk5ejoKwe76c/r7uKLgVGet7JEEC4IBgagBgJ6fH'.
'CxooELByUEbMiQwYALCQkGNECAwIWGExj7KRjAcUE/DRwLlGhg'.
'QALGDigZJtNgIAIvFx4UMDggwJAAFx8kuMjwJ8EC/0IhHGjQRY'.
'iRIQTphBVbyLBDBqcooUadSvUpw6tYs2pVqXJZhoVgmW5Vdo4d'.
'0EdzOnE5cOLgiQ09HCAI0QHBiQ4FEEBIIWCAAQYQCCBo4EAAAQ'.
'gQHChmMEJAgwYLNDBgoEBDAwYIJGuQoDiAZ44DHCgowRACggga'.
'XCgg4DPAAgQKiIEwEEwptmJLlQUwoDUqRtADPAsfTry48eMBgC'.
'tfzrz5cox3u54zBIiKFS8uOhwJkcHFgQ0ukguIINqyAcQNEENI'.
'MALC5PceBEiQcGGzhNHz/zLQkOI9+scOLFBCchAY4BdkBciHUQ'.
'EKQFcCBNC1FeEJyzEYHHGKKfZYeup1iP8YYO+FKOKHHpa44Yko'.
'pqjiiht29tkA0X21wTtA1RNLES6gFcIJCGTwWgAOFHABA3xJUN'.
'J8BCywQAFJrraAAgo0BuUIGkAZQQlQYlnZBQooqeR8EjAQGQSX'.
'jfSgAgVosIADDKiWYQmcZXhQhiu6UKKIYErg5Z589unnn3vmKe'.
'ighBY6qH9kEubAZydJx44qdORjQEUGKHBAB1cS4IIACvSTQgkG'.
'jOCBBqQWkIICHwhA6ggp8FVCCRpccEGaq6ZQgAEFjGCABhG06i'.
'uUAoBaZWofaFBCsSkYm+YH72EZon6TFepPnwrMAAAL2HIAQK++'.
'duttCjOEK+635HYL5bnopqv/7rrsQunlfJOhl2FyMDpFDCFQ3J'.
'jBog40MEAGwS0g5qkXfEBLLQd7EMHCEXzggcILP/wwwxJH/PAF'.
'EzOssQcAdDyDBzy0+oECBCsswAheJttlZSO3C2V83UbAwQ4011'.
'zzDBdorPPCH9hMc847By20xq32ym0KR3sb9LdW5uC0AmECRuai'.
'AZzw1QFoxeJEISAA7EALHHCA7dhks6AtD0NfWzbZaA/N8Ags+L'.
'wDC2rb0KuqJVRQ9gtjv6B32Ry4DQAJNk9g+OEcfAC00HHXbPgH'.
'PCy+sNprr81B25SPPcPQYpN9ucYjcEC4zSRwMEPYAIRJ2AAdzI'.
'hJJT5w3UFoOZAw/7rccnMg+cIz4G6z7rvrbIPPh09gsw1Az+z7'.
'8hXIGjQAjht+w/TUc+CB4kJDv0PxEzyMPe/L487CBb3LPX7wBI'.
'jvvPKFG+8zB1Az0EDVrlcyStehMVD+9tz3b/j5GtMe//pHAsgF'.
'b2HDo5n/ipc4WSlvgQssoOIkJ0DpwQADMcDADShAAvZcL3g8IJ'.
'z0qEcAUX2vghA8XAU+oL3+AeB7DGsc91bogdul0HDNO5UE5tc6'.
'fG3tO6GBgARywD/qGfGINzCcDWD4ARIcDonTs8EHd9YzBU7gBj'.
'AgAQW2aMQYeG8ERYQiFElgwsWVT3oTwEAB1sjGAiSghLXY2cyk'.
't0UZUP/gjaICWhOfKMbqjWAEThzh9EgwxclZ0YgcGEEFuJdEEs'.
'CAi0Yk5AciIBN/ZQAE+JJdAISogAh4wAYV2OAWY0BKUmLgBVuk'.
'wA0qUMiC2cAGHEilLDlYRp3N8Yo7iEEC1ogBGdhxixWAowdGAE'.
'pRUqCUyHzkFkkAx++J8Io3iMEaBUBNAawxAR5sG8N6RkcZPOCb'.
'biQAyBbmSliSUpYWQCaTSkhMEhhTldZz5gi3qMYEcCCJ0iOBG6'.
'dpAV+mkpmiioDAHPAvEABiABIA0sAwpkgK+PIB1GyjAF7wzS2K'.
'c4IFGwEBeunNbz4AB3a0wRs9QACg1RCSbKymBTwqAwzg8WH/DX'.
'1oRNsIA1/KAAZ4xB70RijNalqTjS/V5sLidkWHfhMHEGXS9cj5'.
'AQLsswAPfYAFZurGNxKAAFq0qR1hcNELQM8ERfWlGgsATTtaoI'.
'2vEoAIPvrRF7wxAQaTQGLegrUQTEQBDbhPBHgwzAp0FKI+TZAF'.
'cKCCB9wxjxfgq0Zr6tEaEPYBLXWBARIQqgg05gU4MAEFYJAgnw'.
'rAo1JN0B//6FePUlMDMIDBVGFwVM56MGdE3aAIOvvTqlo1jwEM'.
'40pxwFtrdjUCGNvlLgsA2qnOFJtXxepfvymDCogqhN7cYEd1II'.
'AY/PIBL5hmsNKKAcKaQAU0cKMBFAa1+b3FECDg/5QGTAXcV6HA'.
'oyZwgWR/igITPJYCLIGVrFQVg+9+swY6UIF9H3CDyVKTrydbpB'.
'1lgAL5rlcABnjBd2VgLBe8KlcVeIAKNqwBATxWBSJY64ZpgCtx'.
'Rm57GzQBDfzrzRqIIAGvgrEbNdA2qCJ1gxYQgYB7y6QRYI8HsN'.
'qnATLLWxR0uB8ldmoJILvjDatABiXkwARMIF0Ng7gENPCmCUyA'.
'AWo6RLIAKUANVEBYHewzVikQWANGE4AVbKAAqtklarBpARM84L'.
'siyLEIMECDb/oXBWFO0wV0hYK/ikADZEbqA1BAAGtaNlY2kO6W'.
'ZSACIycgNXneFIR7PAIM2DezNNCBlf8XXQIY1IAGpHopAaBJAc'.
'LyFrIysHMFVkXZXPEqARbYIG9hIAAU7BgHvvVxzgbdYdYgmrAq'.
'QIGwqFkCp2ogATDYsHftqwILpA+kW9Twlhtcg4eqoMPVxFVVWX'.
'ITa77RADnrEmMQ4IAVgIA/jBnPq0pQXy07WbOadWhhSwDmhMiq'.
'BGGlsAZo8OoH4FTYVMJYVskMWTvf4AWzLfetxPmwCjh5w0yGrL'.
'LTFOY3ikQEW4bsY2HdahncYLYfoNLCShCBAth3yzjosgh4y+MS'.
'fm9WAhimAEwgg8ICGsIGANUILl3DWFvZyS0tuSq1jQMduOAFNj'.
'UBuMU7WtbQNpwlyJkOIRD/gA64WwALgEB8rlSwe1Ig5Hemsgqy'.
'rYIagGqkkbsACsJ66NQUXAZKRbA4bcBkpBr9Bh+lgAVcQPWmEq'.
'ACmgVtz31pZI6vUQMmBEBYR45tCjzZBIlT+ZUiUNOiGhfkrw72'.
'JBm2S4cVAAeWJ7ULSkiAEvCA6CTIrIZn7vdWo14GOnA1DgYPdS'.
'1H9I0luN4wWTNcPGZ9YWlewAgcEALTuIAB/SApqUBOgTtv2L9p'.
'/25h+xHklr8csnkWAQ1qUFybYwxvGhjs9Xl+9st/e5ekOhkH0i'.
'5g69uZBpoGFUB8zAPJ38CbmQVeBnBKMpBZMrADw7QrApAAAAB4'.
'mWUCCdFr9ZdU/xfFMMYCKx5QAjwnYMoWLB2GgWniWHZWAy7gZH'.
'4GfrmnfQ0GdVZmYZcGejiAT8bEVRkINEgTdMx3Au4hGtyiUb1k'.
'VDVQAzDwAjDggB9lZy9gbiMgM9K1XCNXUa/lSazhVGqFAuRnR8'.
'z1TTpQQm+kOPf0UThQA5j1aodWVWtkQv13RQCIA0FXTRS1dhRg'.
'A613VSG0A//3AC5mARaAWR6VTua3MKM1AjxAANkmVQvoRh+Qcg'.
'2DVWeHXfQWciOnAbn3gDoQYQBYAx2GTao0PXxEPTGgASXFMK3H'.
'AAEwCgfgNWJCMhdgdt4Ufz8VA6xVcDFgYhcQSEYFWoUFWhjQVY'.
'P2Sv+75FMYkGs2pUpwJCurWGWkUlwCgAGqREYfVDATYFQ4UHfV'.
'5GtP1lIx5lUodougpXgxIGxB8wFqGFpRuDCtVwM8h12kUoCK9g'.
'CHNokjVmodlYQ91j9IFAPBtzhpBgyx42YdEACX8SRMaFSu6FO0'.
'9027eD07VWXd2I1ulUe9c0UdRFvVxFqQRQGJBDIOhIwacJAdtk'.
'FJFE+y4gE7AISkAm7UxFK7mEcLWX2Kt1wthVtUNI5nVY4LE0Ib'.
'pI6SCIZIRSqTiFTU1U+m1WNzRD2zxFW11CsD9UNew0kD2Yoo2V'.
'kawFYP4FKiskhqGGIhpod66FiKZhjXc0s3IFIStYwsNYv/H8QD'.
'rPgAJ6kDOtBhBcBFEyCSfFUBJjl1a/RXFjBSLBRWDdmQaAlD20'.
'STbiSTN+lO1ZeE1ERRvFV3ouZR1NVLfdhjMEV8NeVLrlWMyCcT'.
'DrAd7vaPedVJa1lNbVQAK9WHVkVHN6AqBSkAg6Vo1uZjYtlB2D'.
'RcAmCR36RLz6WWHOlZcbmJ33g9DhNLACgCv5cAzNhRZwVHoZNB'.
'eqiVe+hR06hGf6gz4niHNWmYe4WYMqCY1PSc8YcBukhNLomQ7K'.
'RRw4WbnFWY46SUwHAFPbAOAJNXp8OdUqWHGIABFVABj/RXalQB'.
'TjQ9FPACeuiK6bdbfocBUiRlrLZKTGIDsBhV/w8QThwjOlXGlV'.
'yZn7IUTCY0THf4TemUAMV0i+vlQQ/DGqelA3lGUWAoAmYGdw3z'.
'SrAkZUZFoBjEAXbDOxbqUDWgh9IklVxpaGYmlMxFAjFgAxpVAM'.
'EIWvQIo+7pAD/QAQ0wIxsQGi0gQjnJUps4PX9VAxO1pbc4jdUE'.
'WjQHUmNpl/6USkd0i8b5oE9UnrD2S7PkUhRXcS7JWyaXRAKKXU'.
'ooMVYnAI8pnWWqA3y5PwrEkAtGAXNJPtvjUB31amOlAV4Ja6+2'.
'e2KmpUZUR4/apCbWK13CdT8wEYkAAv9YOygGk1sKeIVFYQIgp2'.
'HKpwUQA9doZf5FARgwcxOWppnaYv8dBoox8EQw6aoOBVLL2aEE'.
'wFj3ZZS+BJcmOny7dJp3xpN4plQsFD1+eVQDVkDQ0372tmUR5w'.
'JbNmk9J2AqkF0GYGfMBaaOelRNils6ND9CIAAQAHkAswC8pVn2'.
'JmBcZIAmcGigsmVbBHPSVpNL5qgChn0oEANbpGjhanKNiAOoFi'.
'wWJgKK+n/ah61Gp28jxp4OM3QWaV+bmHYAAWELqAHCt12eZn3S'.
'xltXZrIf0DuGxWpOuLAW4KExmI65yGDTpEzXSGbTCmEsepF2lk'.
'otZlw9Nklb5wAnIAR50QBNdQIlAAAZhAIWgAI0gAKVhgIYQLU5'.
'tinC0pFaawE0cLVUa27/BgBKwUgD4ke1lZgAzhkDKGCF0UoBpw'.
'YrmkZ4IsoBFYC1lUYDeji2VZtnVptnFJqIJOW2oJdtPZpfabIp'.
'BMArCbJLBrBn4ldpWtlgqiIAkZMAfhsDFZCfGKqHKioC7KQDFl'.
'ABfiu2eYYBSZYAe2sBpqu2cftTbaiiNaWvm2UBXcas4uSpqsM6'.
'QqAmPRAZGVAR8kV4LHG8uEJ4a7QpqdFvCWIR8RdOBnBVv0iaEJ'.
'Zcu7Qp4mUYLLG95zZ6g4h/x+tGhNd6z5YaP9Vo8zZZ6Lkr2MRv'.
'xwuKBNASnYImpmIrkJcAzBtuoJgmvsJQxKdd1vS9XIhk83UTx8'.
'sef0RZ1xtY/7fyYFSVUqQ5UpS0AKpDP/EKAT7AAAWwAexGGR4A'.
'YyPbD3D2eM6LK7XZvIF1bqyHTdeETZPlMCfjAgy8KfERKiOAEx'.
'HgAkqiOB4AE4EYxNeTEI1mw41mMF3iJZ30AbjyR7syWqNXEtDn'.
'wQkwHx1GCy6gUc9Wv0LyuPOxGrVQmcnFvkMXikjDA6tHfKxRvU'.
'ATKlZXmsC3gMNFK08FVARgMBgsP4tCVz8gF9mxDw2AGhc4AreC'.
'wKTCfZIlEvP2bPzWD+YmTuhWMA7jPTmjK6DCKYccdApDAwiQgQ'.
'iQAIxxklDDEtyHJgjgeghAAzwwAvqnJmGSKO4hIBVRAilAWUg2'.
'Av8SUAKw8QEIkCQRVQIFICamzHKCoRr7sABrhgBgByW+gnypPC'.
'k+ASYawMyogWQe8CU6wTPDpFF/VMQFUJm8TL/JZUJppjqLwjoz'.
'sgI/cAIMIAHSwAADsAFIQ1J50GgLaG7U9ItXhce09Vq0oCZ9Ej'.
'XK9wHqQVJJ8s4ltACKsyYB0Crz4wAQoFHiRCQPIwHDFCYLcFWA'.
'QTWgAZDXo1HzcQFXpcsOkGaXMR9SisEKIABAMtEEMBkL8DAQQE'.
'n+Ihry4x4iwgAkXdEA0gDOwwAILROJsmbR8iTnoiRSoiRQ8stE'.
'cyp7PDVVYy+PsgWx8x2g6ZQYIzFcbUDDlliJiD1QHSb/55EiLg'.
'IcGDIv9GIvTUEhF4Ical0vMtLWwZEhncERVpMMSgECO7wpd8ER'.
'xcER9sIUbo0cL+LWgC0cHg0jgK0hj1HUQ7chiBE1IwIgyWEv5F'.
'AjVm2KGzA7AClETM0ufOIuXwIYhPEiwHEXKdEbKHESMTIjvLAO'.
'w7AVWVEVV2MWss0UHQAdKUEMZkEKuU3bvs0OvJAMtJ0VzCAWqy'.
'0jxr3biJ3av/HWxiHXw10jS9AFo+BunV3X6bHT3u0e/+HR0dEM'.
'zhDb4eALex3b1AAFuhAP6mDd7F0IvKAU5p01cIALgwAP74AW8d'.
'0O6QAN+a3fuA0I8KAUyS0bxn3cev3b9n0jZZst3wtBIYpt1y4S'.
'2NSd2WdBD9YxBesdKUOAC9xgDddxC35QBXcA4jbi4GQQ4mnhBK'.
'+A36xAFPo9Dx3u4WDg4vI9208xFart3D2uEtNRHXOAD0Re5EZ+'.
'5Eie5HwQCiz+4g4+BEEAADs='.
'');
}

function htmlhead() {
global $ver;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
  <head>
    <title>
       base64img v<? echo $ver; ?> by php.holtsmark.no
    </title>
  </head>
  <body bgcolor=FFFFFF>

	<table border="1" cellpadding="0" cellspacing="0" bordercolor="#bebec9">
<tr>
<td>
<p align="right"><font face="Verdana" size="1"><a 
href="http://php.holtsmark.no/base64img/"><img 
src="<? echo $_SERVER["PHP_SELF"]; ?>?i=base64imglogo" border="0"
alt="base64img v<? echo $ver; ?>"></a><br>
&nbsp;version <? echo $ver; ?>&nbsp;<br></font></p>
</td>
</tr>
</table>

	
<?
}
 #phpinfo();

$size = 0;
$imgcode="";

#run this with:
if (array_key_exists("i", $_REQUEST ) && $_REQUEST["i"] == "holtsmarklogo") 
	{ holtsmarklogo(); die(); }
if (array_key_exists("i", $_REQUEST ) && $_REQUEST["i"]== "base64imglogo") 
	{ base64imglogo(); die(); } 

if ( array_key_exists( "submit", $_POST ))
{
	$uploadfile = "";
	
	if (array_key_exists("uploadfile", $_FILES ) ) $uploadfile = $_FILES["uploadfile"]["tmp_name"];
	if ( array_key_exists("uploadfile", $_FILES )) $uploadfile_name = $_FILES["uploadfile"]["name"];
	if ($_FILES["uploadfile"]["name"] == "none")
	die("<font face=verdana size=2><font color=red>
	Error: Could not fetch uploaded file</font>
	<br>Please <a href=\"".$_SERVER["PHP_SELF"]."\">go back</a> and try again!
	<br><br><b>base64img</b> v$ver is created by<br>
			<img src=\"".$_SERVER["PHP_SELF"]."?i=holtsmarklogo\">");
	if (!File_exists ("$uploadfile"))
		die("<font face=verdana size=2><font color=red>
			Error: Could not fetch uploaded file, wrong premissions 
			or pathname is incorrect...</font><br>
			Point variable \$f to a filename!<br><br> \$f = $f 
			<br>\$uploadfile = $uploadfile<br>
			\$uploadfile_name = $uploadfile_name<br>
			or disable \$f in the file. 
			<br><br><b>base64img</b> v$ver is created by<br>
			<img src=\"".$_SERVER["PHP_SELF"]."?i=holtsmarklogo\">");

  function showphpcode() 
  {
	  global $c,$size,$imgcode;
    echo "<?
# Copy and paste this code
# into the TOP of your
# php-script!
";
    echo "
  function img() 
  {
    header(\"Content-type: image/$c\");
    header(\"Content-length: $size\");
    echo base64_decode(
'";
    echo $imgcode;
    echo "');
  }
# Above is the code of
# your image..

# Here is a Example:
# This is the phpcode to
# execute function img(); :
  if ( array_key_exists(\"image\", \$_REQUEST) && \$_REQUEST[\"image\"]==1) { img(); die(); }

# End PHP ?>
  <img src=<?php echo \$_SERVER[\"PHP_SELF\"]; ?>?image=1><br> 
  Created with <a href=http://php.holtsmark.no>
  base64img.php</a>
  ";
}

if ( $uploadfile != "" )
{
	$fd = fopen ($uploadfile, "rb");
	$size=filesize ($uploadfile);
	$c=substr("$uploadfile_name",strrpos($uploadfile_name,".")+1);
	$cont = fread ($fd, $size);
	fclose ($fd);
	$encimg=base64_encode($cont);
	$imgcode=chunk_split("$encimg",$LineLength,"'.
'");
}

if (array_key_exists("do", $_POST) && $_POST["do"] == "send") 
{
  header( "Content-type: application/x-httpd-php" );
  header( "Content-Disposition: attachment; filename=testbase64img.php" );
  # header( "Content-Description: PHP3 Generated Data" );

  showphpcode();
  die();
}
if ( array_key_exists("do", $_POST) && $_POST["do"] == "show") 
{
  $LineLength+=5;

htmlhead();

 echo '<form>
  <textarea cols="'.$LineLength.'" name="t" rows="20">';
  showphpcode();
  echo '  </textarea><br>
  <input type="button" value="Select All" onclick="t.focus();t.select();">
 </form>';
 } 
}
else 
{
  htmlhead();
}

if (!array_key_exists("submit", $_POST )) { ?>
<form enctype="multipart/form-data" method="POST">
<font face="Verdana" size="2">
<br>
<? 

if (array_key_exists("f", $_POST )) 
{ 
  echo basename($_SERVER["PHP_SELF"]);
  $f =$_POST["f"];
  echo " is configured to encode $f <br>Edit "; 
  echo basename($_SERVER["PHP_SELF"]);
  echo " to enable upload functionality";
}

if (!array_key_exists("f", $_POST )) 
{ 
  ?>
  &nbsp;<b>Select file (gif/jpg) to encode:</b><br>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="File" name="uploadfile" size="5"> 
  <? 
} 
?>
<br><br>
&nbsp;<b>Select how you want your code served:</b><br>
&nbsp;&nbsp;&nbsp;&nbsp;Download code: <input type="radio" value="send" checked name="do"><br>
&nbsp;&nbsp;&nbsp;&nbsp;Show code: <input type="radio" value="show" name="do"><br><br>

<input type="hidden" name="submit" value="encode">
<input type="submit" name="send" value="upload and encode">
</form>
<? 
} 
else {?>
<br><a href="<? echo $_SERVER["PHP_SELF"]; ?>">Go back</a><br>
<? } ?><br>

  <font size="1" face="Verdana">base64img v<? echo $ver ?> <br></font>
    <a href="http://php.holtsmark.no">
		  <img src="<? echo $_SERVER["PHP_SELF"]; ?>?i=holtsmarklogo" border="0">
		</a></font>
  </body>
</html>




