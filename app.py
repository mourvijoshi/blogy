from flask import Flask, render_template, request, redirect, url_for
from flask_mysqldb import MySQL
from datetime import datetime

app = Flask(__name__)

# Configure MySQL database
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'blogy'

mysql = MySQL(app)


@app.route('/')
def index():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM Blogpost ORDER BY date_posted DESC")
    posts = cur.fetchall()
    cur.close()

    valid_posts = []
    for post in posts:
        try:
            date_posted = datetime.strptime(post[4], '%Y-%m-%d %H:%M:%S')
            valid_posts.append((post[0], post[1], post[2], post[3], date_posted, post[5]))
        except ValueError:
            print(f"Invalid date format for post ID {post[0]}: {post[4]}")

    print(f"Valid posts: {valid_posts}")
    return render_template('index.html', posts=valid_posts)



@app.route('/post/<int:post_id>')
def post(post_id):
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM Blogpost WHERE id = %s", (post_id,))
    post = cur.fetchone()
    cur.close()

    try:
        date_posted = datetime.strptime(post[4], '%Y-%m-%d %H:%M:%S')
    except ValueError:
        date_posted = None

    post = (post[0], post[1], post[2], post[3], date_posted, post[5])

    return render_template('post.html', post=post)


@app.route('/add')
def add():
    return render_template('add.html')


@app.route('/delete')
def delete():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM Blogpost ORDER BY date_posted DESC")
    posts = cur.fetchall()
    cur.close()
    return render_template('delete.html', posts=posts)


@app.route('/addpost', methods=['POST'])
def addpost():
    title = request.form['title']
    subtitle = request.form['subtitle']
    author = request.form['author']
    content = request.form['content']

    cur = mysql.connection.cursor()
    cur.execute("INSERT INTO Blogpost (title, subtitle, author, content, date_posted) VALUES (%s, %s, %s, %s, %s)",
                (title, subtitle, author, content, datetime.now()))
    mysql.connection.commit()
    cur.close()
    return redirect(url_for('index'))


@app.route('/deletepost', methods=['DELETE', 'POST'])
def deletepost():
    post_id = request.form.get("post_id")

    cur = mysql.connection.cursor()
    cur.execute("DELETE FROM Blogpost WHERE id = %s", (post_id,))
    mysql.connection.commit()
    cur.close()
    return redirect(url_for('index'))


if __name__ == '__main__':
    app.run(debug=True)
