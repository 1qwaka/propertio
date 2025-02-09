# Stage 1: Use a full-featured image to create and set permissions
FROM alpine as builder

# Create the /traces directory and set permissions
RUN mkdir -p /traces && chmod -R 777 /traces

# Stage 2: Use the minimal otel/opentelemetry-collector image
FROM otel/opentelemetry-collector

# Copy the /traces directory from the builder stage
COPY --from=builder /traces /traces

# Switch to root if needed to verify permissions
#USER root
#RUN ls -ld /traces

# Switch back to the non-root user (if required)
#USER otel
