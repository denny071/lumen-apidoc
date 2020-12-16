<h1 align="center"> lumen-文件生成器 </h1>

<p align="center"> 基于lumen框架的文档生成器.</p>


## 安装

```shell
$ composer require denny071/lumen-apidoc -vvv
```

## 使用规范

```php 
//@F  order
//@F  no	                订单编号
//@F  oupon_id	            优惠券ID
//@F  type	                类型（normal：正常，seckill：秒杀，crowdfund：众筹）
//@F  express_company	    物流公司
//@F  express_freight	    物流费用
//@F  extra	                扩展信息
//@F  is_closed	            是否关闭
//@F  is_reviewed	        是否评论
//@F  paid_at	            支付时间
//@F  pay_deadline	        支付截止时间
//@F  payment_method	    支付方式
//@F  payment_no	        支付单号
//@F  refund_status	        退款状态
//@F  refund_no	            退款单号
//@F  refund_status	        退款状态
//@F  remark	            备注
//@F  ship_data	            物流数据
//@F  ship_status	        物流状态
//@F  status	            状态
//@F  total_amount	        订单总金额
//@F  created_at	        创建时间
//@F  updated_at	        更新时间
//@F  address:              用户地址
//@F    address	            详细地址
//@F    contact_name	    联系人
//@F    contact_phone	    联系电话
//@F    zip	                邮编
//@F  items:                产品列表
//@F    amount	            金额
//@F    created_at	        创建时间
//@F    id	                商品ID
//@F    order_id	        订单ID
//@F    price	            价格
//@F    product_id	        产品ID
//@F    product_sku_id	    产品SKU_ID
//@F    rating	            评价
//@F    review	            评论
//@F    reviewed_at	        评价时间
//@F    updated_at	        更新时间


//@Psettlement-订单结算-settlement-订单结算
//@A[I-express_id-快递ID,I-address_id-地址ID,A-product_sku_ids-商品ID]
//@R[product_name-商品列表(product_list)@商品名称,product_sku_name-商品列表(product_list)@商品SKU名称]
//@R[id-默认地址(default_address)@地址ID,user_id-默认地址(default_address)@用户ID]
//@R[province-默认地址(default_address)@省名称,province_code-默认地址(default_address)@省代码]
//@R[city-默认地址(default_address)@城市,city_code-默认地址(default_address)@城市代码]
//@R[district-默认地址(default_address)@区名称,district_code-默认地址(default_address)@区代码]
//@R[address-默认地址(default_address)@详细地址,zip-默认地址(default_address)@邮编]
//@R[contact_name-默认地址(default_address)@联系人,contact_phone-默认地址(default_address)@联系电话]
//@R[is_default-默认地址(default_address)@是否默认,last_used_at-默认地址(default_address)@最后使用时间]
//@R[created_at-默认地址(default_address)@创建时间,updated_at-默认地址(default_address)@更新时间]
//@R[full_address-默认地址(default_address)@完整地址]
//@R[express_freight-运输重量,product_price-商品价格,total_price-总价格]
//@M[config:user]

```
 
## License

MIT



