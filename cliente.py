import SOAPpy
namespace = "http://appserver-01.alunos.di.fc.ul.pt/~asw014"
url = "http://appserver-01.alunos.di.fc.ul.pt/~asw014/index.php/Nusoapserver?wsdl"
server = SOAPpy.SOAPProxy(url,namespace)

def info_partida(gameid,username):
    return server.Service.info_partida(gameid,username);

def aposta_jogo(gameid,username,password,jogada,valor):
    return server.Service.aposta_partida(gameid,username,password,jogada,valor)
def login(username,password):
    return server.Service.login(username,password)
def string_parser(string):
    dici = {}
    poker = string.split(" ")
    keys = ["lobbyname","id","startdate", "startime","tb1","tb2","tb3","tb4","tb5"
            ,"current_player","current_bet","current_pot","mc1","mc2","game_state","players_stacks"]
    for i in range(len(poker)):
        dici[keys[i]] = poker[i]       
        
    return dici
def show_game(d):
    print " _______________________________________"
    print "lobby: " + d['lobbyname']
    print "started at: " + d['startdate'] + " "+ d['startdate']
    if d['game_state'] == 'pre-flop':
        print "Cards On Table:    "
    elif d['game_state'] == 'flop':
        print "Cards On Table: " + d['tb1'] + " " + d['tb2']+ " " +d['tb3']
    elif d['game_state'] == 'turn':
        print "Cards On Table: " + d['tb1'] + " " + d['tb2']+ " " +d['tb3']+ " " +d['tb4']
    else:
        print "Cards On Table: " + d['tb1'] + " " + d['tb2']+ " " +d['tb3']+ " " +d['tb4']+ " " +d['tb5']

    print "My Cards: " + d['mc1']+ " " + d['mc2']
    print "Current player: " + d['current_player']
    print "Current bet: " + d['current_bet']
    print "Current pot: " + d['current_pot']
    print "Players Bets:_______________________ "
    players_stacks = d['players_stacks'].split("*")
    players_stacks.pop(0)
    for player_stack in players_stacks:
        print "     " + player_stack + " $$$$"
    print " _______________________________________"
    
print "Welcome to Poker UnderGround"
username = raw_input("username: >>>")
password = raw_input("password: >>>")
sucess = login(username, password)
while(sucess != True):
    print "wrong password/username"
    username = raw_input("username: >>>")
    password = raw_input("password: >>>")
    sucess = login(username, password)
option = 0
try:
    while (True):
        print "What you wanna do?"
        print "1 - Get Table Info"
        print "2- Make a Move"
        print "3- Exit"
        option = raw_input("option >>")

        if option == "1":
            gameid = raw_input("Game id? >>")
            info = info_partida(gameid,username)
            show_game(string_parser(info))
        elif option == "2":
            jogada = "0";
            gameid = raw_input("Game id? >>")
            while jogada != "6":
                info = info_partida(gameid,username)
                show_game(string_parser(info))
                print "what are u going to do?"
                print "1-Check"
                print "2-Call"
                print "3-Bet/Raise"
                print "4-ALL-IN"
                print "5-Fold"
                print "6-Go back to Menu"
                jogada = raw_input("Your move >>")
                if jogada == '1':
                    print aposta_jogo(gameid, username,password,"check", 0)
                elif jogada == '2':
                    print aposta_jogo(gameid, username,password,"call", 0)
                elif jogada == '3':
                    bet = raw_input("How much? >>")
                    print aposta_jogo(gameid,username,password,"bet",bet)
                elif jogada == '4':
                    print "You sure you want to all-in?"
                    print "1-YES, wish me luck"
                    print "2-NO"
                    all_in_choice = raw_input("all_in_choice >>")
                    if all_in_choice == '1':
                        print aposta_jogo(gameid, username,password,"allin", 0)
                    else:
                        print "all-in canceled"
                        continue;
                elif jogada == "5":
                    print aposta_jogo(gameid, username,password,"fold", 0)
                elif jogada == "6":
                    break;
                else:
                    "Unknown command"
        elif option == "3":
            print "Goodbye " + username + ", go to http://appserver-01.alunos.di.fc.ul.pt/~aswo014"
            print "for the real Poker Experience"
            break;
        else:
            print "Unknown command!!"
            print "What you wanna do?"
            print "1 - Get Info"
            print "2- Make a Move"
            print "3- Exit"
            option = raw_input("option >>")
except:
    print "Something went wrong, maybe the game you requested hasnt started yet, or it has ended, please reboot the client"




