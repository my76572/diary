<?php
class  Tcp{
    const HOST = '0.0.0.0';
    const PORT = '9501';
    private $tcp = null;   //实例化的swoole的tcp服务器
    private $process;
    private $testProcess;
    public function __construct()
    {
        $this -> tcp = new swoole_server(self::HOST, self::PORT, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->tcp->set(array(
            'worker_num' => 4,    //进程数 服务器核心数的1-4倍
            //work进程在处理完1000次后，结束本wprker进程，然后manager重新启动新的worker进程，防止内存溢出 
            'max_request' => 1000, 
            'log_file' => "./tcpserver.log",
            //'daemonize' => true,
            )
        );

 
        // $server = $this->tcp;
        // $this->process = new swoole_process(function($process) use ($server) {
        //     while (true) {//我猜测这里的一直循环,是不想浪费太多的资源，这里的资源暂时我还没有想到，想到后再更新。
        //         print_r("进程1\n");
        //         $msg = $process->read();//同步的，读取不到数据，就不往下执行.
        //         foreach($server->connections as $conn) {
        //             $server->send($conn, $msg);
        //         }
        //     }
        // });
        // $this->testProcess = new swoole_process(function($testProcess) use ($server){
        //     while (true) {
        //         print_r("进程2\n");
        //         $msg = $testProcess->read();
        //         foreach($server->connections as $conn) {
        //             $server->send($conn, $msg);
        //         }
        //     }
        // }); 
        // $this->tcp->addProcess($this->process);
        // $this->tcp->addProcess($this->testProcess);


        $this->tcp->on('connect', [$this, 'onConnect']);      //事件连接
        $this->tcp->on('receive', [$this, 'onReceive']);      //监听数据接收事件
        $this->tcp->on('close', [$this, 'onClose']);          //监听连接关闭事件

        $this->tcp->start();                                  //启动服务


    }
    /**
     * 监听连接事件
     * @param $tcp
     * @param $fd
     * @param $reactor_id
     */
    public function onConnect($tcp, $fd, $reactorId)
    {
        echo "客户端id:{$fd}连接成功,来自于线程{$reactorId}\n";
    }

    /**
     * 监听接收事件
     * @param $tcp
     * @param $fd
     * @param $reactor_id
     * @param $data
     */
    public function onReceive($tcp, $fd, $reactorId, $data)
    {
        echo "接收到了客户端id: {$fd} 发送的数据：{$data}";
        $sendData = "服务端将客户端发送的数据原样返回：{$data}";
        //$tcp_port = $this -> tcp ->addListener("0.0.0.0", 0, SWOOLE_SOCK_TCP); 
        //$port = $tcp_port -> port;
        //$this->process->write($data);
        //$this->testProcess->write("123");
        //print_r("主进程\n");
        $tcp->send($fd, $sendData);
    }

    /**
     * 监听关闭事件
     * @param $tcp
     * @param $fd
     */
    public function onClose($tcp, $fd)
    {
        echo "客户端id: {$fd} 关闭了连接\n";
    }

}

$tcp = new Tcp();

