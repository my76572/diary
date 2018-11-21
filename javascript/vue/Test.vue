<template>
	<div class="footer">
		<div v-for='(item,index) of items' :class='[item.cls,{on:index === idx}]' @click="$router.push(item.push)">
			<p :class="['colorChange',{on:index===idx}]" >{{item.name}}</p>
		</div>
 
	</div>
</template>
 
<script type="text/javascript">
	
	export default{
		props:['idx'],
		data(){
			return {
				items:[{
					cls:"home",
					name:"首页",
					push:"/hello1"
				},
				{
					cls:"shares",
					name:"案例",
					push:"/hello2"
				},
				{
					cla:"community",
					name:"我们",
					push:"/"
				},
				{
					cla:"mine",
					name:"我的",
					push:"/hello"
				}]
			}
    },
    //在页面初始化的时候，所进行的操作
    created(){
      var resutl = this.getFooter();
      console.log(resutl);
    },

     methods:{
         //跨域post请求
        getFooter:function(){
          this.$http.post('http://www.tp.com/test',{name:'s',email:'2'},{emulateJSON: true}).then((res)=> {   
            console.log(res.body);
          });
        }
      }

  }
  
  

 
</script>
<style>
.footer{
	display: flex;
	position: absolute;
	left: 0;
	bottom: 0;
	box-sizing: border-box;
	height: 3rem;
	background: #909090;
	width: 100%;
  }
	.footer div{
		flex: 1;
		font-size: 1rem;
	}
	div img{
		width: 30px;
		height: 30px;
	}
	div p{
		color:black;
    text-align:center;
	}
	.on{
		color: red;
	}
</style>
