#hitlib2
##这是一个希望哈工大图书馆网站变得更好的项目

###[工大校内测试站点](http://202.118.251.53/hitlib2)

###支持现代浏览器访问，手机浏览器自动跳转到移动站点

PC截图

![PC截图](https://github.com/HIT-ON-Github/hitlib2/raw/master/screenshot-pc.png)

移动设备截图(iPod Touch 4, iOS 6.1.5)

![移动设备截图](https://raw.githubusercontent.com/HIT-ON-Github/hitlib2/master/screenshot-mobile.png)

###没错，百度，哥抄的就是你！

###附带Python脚本一枚

不想看PHP代码的可以看看Python代码。我知道大家都喜欢Python。

示例代码：

查询

```python
>>> from hitlib2 import query
>>> a = query("网页")
>>> b = query("读者","qk")
>>> c = query("软件","lw")
>>> a
[{'publisher': u'\u673a\u68b0\u5de5\u4e1a\u51fa\u7248\u793e', 'author': u'\u5218\u745e\u65b0 \
......
```

使用prettyprint打印查询结果

```python
>>> from hitlib2 import prettyprint
>>> prettyprint(a)
网页设计与制作教程
网页设计与制作
......
>>> prettyprint(a,"author")
刘瑞新 张兵义主编 刘瑞新 张兵义
张国庆主编 张国庆
......
>>> 

```
