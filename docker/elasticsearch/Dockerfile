FROM docker.elastic.co/elasticsearch/elasticsearch-oss:6.5.4

RUN bin/elasticsearch-plugin install analysis-kuromoji && \
	bin/elasticsearch-plugin install analysis-icu && \
	bin/elasticsearch-plugin install analysis-smartcn
