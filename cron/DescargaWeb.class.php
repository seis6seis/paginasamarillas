<?php
class DescargaWeb {
	public $Proxy=array(array());
	public $ProxyCount=0;
	public $getinfo="";
	private $ProxyCon=0;
	private $ProxyIP='';
	private $ProxyPort='';
	private $ProxyProtocol='';
	
	function DescargaWeb(){
		$this->ObtenerProxy();
	}
	
	function CURL($URL){
		$Salir=0;
		do{
			ob_flush();
			flush();
			$c = curl_init($URL);
			curl_setopt($c, CURLOPT_TIMEOUT, 30);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)");
			curl_setopt($c, CURLOPT_HTTPHEADER, array("Accept-Language: es-es,en"));
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($c, CURLOPT_PROXY, $this->ProxyIP);
			curl_setopt($c, CURLOPT_PROXYPORT, $this->ProxyPort);
			curl_setopt($c, CURLOPT_PROXYTYPE, $this->ProxyProtocol);
			echo "*".$URL."*\n";
			$web = curl_exec($c);
			$this->getinfo=curl_getinfo($c);
			
			if ($this->getinfo['http_code']==500) { $Salir=1; break; }
			if ($this->getinfo['http_code']==404) { $Salir=1; break; }
			if ($this->getinfo['http_code']!=200) echo "ERROR abrir Web: ".$this->getinfo['http_code']."\n";
			curl_close($c);
			
			if(strlen($web)==0) {
				if ($this->ProxyIP!=''){
					$this->ProxyIP='';
					$this->ProxyPort='';
					$this->ProxyProtocol='';
					
					fwrite ($fclog, "<p class='error'>Desactivar Proxy</p>\n");
					echo "Desactivar Proxy\n";
				}else{
					$this->ProxyCon++;
					$this->ProxyIP= $this->Proxy[$this->ProxyCon]['IP'];
					$this->ProxyPort=$this->Proxy[$this->ProxyCon]['Port'];
					$this->ProxyProtocol=$this->Proxy[$this->ProxyCon]['Protocol'];
				
					fwrite ($fclog, "<p class='error'>Cambiar de Proxy: ".$this->ProxyIP.":".$this->ProxyPort."  ".$this->ProxyProtocol."</p>\n");
					echo "Cambiar de Proxy: ".$this->ProxyIP.":".$this->ProxyPort."  ".$this->ProxyProtocol."\n";
				}
			}
		}while($this->getinfo['http_code']!=200 && $Salir==0);
		
		return $web;
	}
	
	function ObtenerProxy() {
		$this->Proxy[$this->ProxyCount]['IP']="";
		$this->Proxy[$this->ProxyCount]['Port']="";
		$this->Proxy[$this->ProxyCount]['Protocol']="";
		$this->ProxyCount=1;
		$Fich=fopen("proxy.txt","r");
		while(!feof($Fich)){
			$Linea=fgets($Fich);
			$trozos=explode(";",$Linea);
			
			$this->Proxy[$this->ProxyCount]['IP']=$trozos[0];
			$this->Proxy[$this->ProxyCount]['Port']=$trozos[1];
			if($trozos[2]=="HTTP" || $trozos[2]=="HTTPS") $this->Proxy[$this->ProxyCount]['Protocol']=CURLPROXY_HTTP;
			if($trozos[2]=="socks4/5") $this->Proxy[$this->ProxyCount]['Protocol']=CURLPROXY_SOCKS5;
			
			$this->ProxyCount++;
		}
		fclose($Fich);
	}
	
	function ListarProxy(){
		for ($i=0;$i<=$this->ProxyCount-1;$i++){
			echo " - ".$this->Proxy[$i]['IP'].":".$this->Proxy[$i]['Port']."  ".$this->Proxy[$i]['Protocol']."\n";
		}
	}
}
?>
