FROM python:3.11 

WORKDIR /app

RUN pip install --no-cache-dir psycopg2-binary tabulate 

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt 

COPY . .

EXPOSE 5001
CMD ["flask", "run", "--host=0.0.0.0", "--port=5001"]