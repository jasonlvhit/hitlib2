#hitlib2
##这是一个希望哈工大图书馆网站变得更好的项目

###[工大校内测试站点](http://202.118.251.53/hitlib2)

###支持现代浏览器访问，手机浏览器自动跳转到移动站点

PC截图

![PC截图](https://github.com/HIT-ON-Github/hitlib2/raw/master/screenshot-pc.png)

移动设备截图(iPod Touch 4, iOS 6.1.5)

![移动设备截图](https://raw.githubusercontent.com/HIT-ON-Github/hitlib2/master/screenshot-mobile.png)

###没错，百度，哥抄的就是你！

附带Python脚本一枚，不想看PHP代码的可以看看Python代码。我知道大家都喜欢Python。


示例

```python
>>> from hitlib2 import query
>>> a = query("网页")
>>> b = query("读者",qk")
>>> c = query("软件","lw")
>>> a
[{'publisher': u'\u673a\u68b0\u5de5\u4e1a\u51fa\u7248\u793e', 'author': u'\u5218\u745e\u65b0 \
.......
>>> from hitlib2 import prettyprint
>>> prettyprint(a)
网页设计与制作教程
网页设计与制作
Dreamweaver+Flash+Photoshop网页设计从入门到精通
网页设计与制作
DIV+CSS 3.0网页布局实战从入门到精通
网站规划与动态网页设计
Dreamweaver网页设计与制作
网站规划与网页设计
中文版Dreamweaver CS6  Flash CS6  Photoshop CS6网页设计基础培训教程
巧学巧用Dreamweaver CS6制作网页
>>> prettyprint(a,"author")
刘瑞新 张兵义主编 刘瑞新 张兵义
张国庆主编 张国庆
李东博编著 李东博
修毅 洪颖 邵熹雯编著 修毅 洪颖 邵熹雯
新视角文化行编著
舒后 何薇编著 舒后 何薇
祁瑞华主编 祁瑞华
杜永红主编 杜永红
何秀芳编著 何秀芳
刘西杰编著 刘西杰
>>> 

```