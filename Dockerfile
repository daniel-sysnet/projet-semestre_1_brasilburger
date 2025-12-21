# ===== BUILD =====
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src

COPY csharp_web.sln .
COPY csharp_web/csharp_web.csproj csharp_web/
RUN dotnet restore csharp_web/csharp_web.csproj

COPY . .
WORKDIR /src/csharp_web
RUN dotnet publish -c Release -o /app/publish

# ===== RUNTIME =====
FROM mcr.microsoft.com/dotnet/aspnet:8.0
WORKDIR /app
COPY --from=build /app/publish .

ENV ASPNETCORE_URLS=http://+:8080
EXPOSE 8080

ENTRYPOINT ["dotnet", "csharp_web.dll"]
