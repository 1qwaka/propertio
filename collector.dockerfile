# Use Alpine Linux as the base image
FROM alpine:latest

# Set environment variables
ENV OTELCOL_VERSION=0.118.0
ENV OTELCOL_BINARY=otelcol_${OTELCOL_VERSION}_linux_amd64.tar.gz
ENV OTELCOL_URL=https://github.com/open-telemetry/opentelemetry-collector-releases/releases/download/v${OTELCOL_VERSION}/${OTELCOL_BINARY}

# Install dependencies
RUN apk update && \
    apk add --no-cache curl tar
#    rm -rf /var/cache/apk/*

# Download and install otelcol
RUN curl --proto '=https' --tlsv1.2 -fOL ${OTELCOL_URL} && \
    tar -xvf ${OTELCOL_BINARY} && ls -la && \
    chown root /otelcol && chmod 777 /otelcol && \
    ls -la
#    mv otelcol_linux_amd64 /usr/local/bin/otelcol && \
#    chmod +x /usr/local/bin/otelcol && \
#    rm -rf ${OTELCOL_BINARY}

# Create a directory for traces (optional)
# RUN mkdir -p /traces && chmod -R 777 /traces

# Set the entrypoint to run otelcol
ENTRYPOINT ["/otelcol"]

# Default arguments for otelcol (you can override these when running the container)
CMD ["--config=/etc/otelcol/config.yaml"]
